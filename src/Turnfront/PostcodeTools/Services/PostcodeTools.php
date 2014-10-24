<?php
/**
 * @file 
 */

namespace Turnfront\PostcodeTools\Services;


use Turnfront\PostcodeTools\Contracts\PostcodeToolsInterface;

class PostcodeTools implements PostcodeToolsInterface {

  /**
   * Allows you to split up a postcode so that we can search the database for it.
   *
   * Based on code from StackOverflow http://stackoverflow.com/questions/14903802/mysql-postcode-search
   *
   * @param $postcode
   *
   * @return array
   */
  public function parsePostcode($postcode) {
    $postcode = preg_replace('/\s*/', '', strtoupper($postcode));

    $sector  = substr($postcode, 0, -2);
    $outcode = $district = substr($sector, 0, -1);

    $incode = substr($postcode, -3);

    return array(
      'postcode'  => $postcode,
      'formatted' => $outcode . ' ' . $incode,
      'district'  => $district,
      'sector'    => $sector,
      'outcode'   => $outcode,
      'incode'    => $incode,
    );
  }

  /**
   * Check whether a provided postcode is valid.
   *
   * From http://www.braemoor.co.uk/software/postcodes.shtml
   *
   * @param $toCheck
   *
   * @return bool
   */
  public function checkPostcode($toCheck){
      // Permitted letters depend upon their position in the postcode.
      $alpha1 = "[abcdefghijklmnoprstuwyz]";                          // Character 1
      $alpha2 = "[abcdefghklmnopqrstuvwxy]";                          // Character 2
      $alpha3 = "[abcdefghjkpmnrstuvwxy]";                            // Character 3
      $alpha4 = "[abehmnprvwxy]";                                     // Character 4
      $alpha5 = "[abdefghjlnpqrstuwxyz]";                             // Character 5
      $BFPOa5 = "[abdefghjlnpqrst]{1}";                               // BFPO character 5
      $BFPOa6 = "[abdefghjlnpqrstuwzyz]{1}";                          // BFPO character 6

      // Expression for BF1 type postcodes
      $pcexp[0] =  '/^(bf1)([[:space:]]{0,})([0-9]{1}' . $BFPOa5 . $BFPOa6 .')$/';

      // Expression for postcodes: AN NAA, ANN NAA, AAN NAA, and AANN NAA with a space
      $pcexp[1] = '/^('.$alpha1.'{1}'.$alpha2.'{0,1}[0-9]{1,2})([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';

      // Expression for postcodes: ANA NAA
      $pcexp[2] =  '/^('.$alpha1.'{1}[0-9]{1}'.$alpha3.'{1})([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';

      // Expression for postcodes: AANA NAA
      $pcexp[3] =  '/^('.$alpha1.'{1}'.$alpha2.'{1}[0-9]{1}'.$alpha4.')([[:space:]]{0,})([0-9]{1}'.$alpha5.'{2})$/';

      // Exception for the special postcode GIR 0AA
      $pcexp[4] =  '/^(gir)([[:space:]]{0,})(0aa)$/';

      // Standard BFPO numbers
      $pcexp[5] = '/^(bfpo)([[:space:]]{0,})([0-9]{1,4})$/';

      // c/o BFPO numbers
      $pcexp[6] = '/^(bfpo)([[:space:]]{0,})(c\/o([[:space:]]{0,})[0-9]{1,3})$/';

      // Overseas Territories
      $pcexp[7] = '/^([a-z]{4})([[:space:]]{0,})(1zz)$/';

      // Anquilla
      $pcexp[8] = '/^ai-2640$/';

      // Load up the string to check, converting into lowercase
      $postcode = strtolower($toCheck);

      // Assume we are not going to find a valid postcode
      $valid = false;

      // Check the string against the six types of postcodes
      foreach ($pcexp as $regexp) {

        if (preg_match($regexp,$postcode, $matches)) {

          // Load new postcode back into the form element
          $postcode = strtoupper ($matches[1] . ' ' . $matches [3]);

          // Take account of the special BFPO c/o format
          $postcode = preg_replace ('/C\/O([[:space:]]{0,})/', 'c/o ', $postcode);

          // Take acount of special Anquilla postcode format (a pain, but that's the way it is)
          if (preg_match($pcexp[7],strtolower($toCheck), $matches)) $postcode = 'AI-2640';

          // Remember that we have found that the code is valid and break from loop
          $valid = true;
          break;
        }
      }

      // Return with the reformatted valid postcode in uppercase if the postcode was
      // valid
      if ($valid){
        $toCheck = $postcode;
        return true;
      }
      else return false;

  }

} 