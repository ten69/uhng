<?php
namespace frontend\tests;


class FunctionalTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions;


    public function seeValidationError($message)
    {
        $this->see($message, '.help-block');
    }

    public function dontSeeValidationError($message)
    {
        $this->dontSee($message, '.help-block');
    }
}
