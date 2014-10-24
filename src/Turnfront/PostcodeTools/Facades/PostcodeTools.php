<?php


namespace Turnfront\PostcodeTools\Facades;

use Illuminate\Support\Facades\Facade as Facade;

class PostcodeTools extends Facade {

  protected static function getFacadeAccessor(){
    return "Turnfront\\PostcodeTools\\Contracts\\PostcodeToolsInterface";
  }

}