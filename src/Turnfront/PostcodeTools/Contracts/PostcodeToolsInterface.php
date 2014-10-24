<?php
/**
 * @file
 */
namespace Turnfront\PostcodeTools\Contracts;

interface PostcodeToolsInterface {
  /**
   * Allows you to split up a postcode so that we can search the database for it.
   *
   * Based on code from StackOverflow http://stackoverflow.com/questions/14903802/mysql-postcode-search
   *
   * @param $postcode
   *
   * @return array
   */
  public function parsePostcode($postcode);
}