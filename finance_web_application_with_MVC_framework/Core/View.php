<?php

namespace Core;

class View {

    public static function render($view, $args = []) {

        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/View/$view";

        if(is_readable($file)){
            require $file;
        }
        else {
            throw new \Exception("$file not found");
        }
    }

    public static function renderTemplate($template, $args = []) {

        echo static::getTemplate($template, $args);
    }

    public static function getTemplate($template, $args = []) {

        static $twig = null;

        if($twig === null) {

            $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig\Environment($loader);
            $twig->addGlobal('current_user', \App\Authentication::getUser());
            $twig->addGlobal('flash_messages', \App\Flash::getMessages());
            $twig->addGlobal('getSumResults', \App\Models\Balance::getResultsToShow('sumResults'));
            $twig->addGlobal('getExpenseResults', \App\Models\Balance::getResultsToShow('expenseResults'));
            $twig->addGlobal('getIncomeResults', \App\Models\Balance::getResultsToShow('incomeResults'));
            $twig->addGlobal('getExpenseChartData', \App\Models\Balance::getChartData('expenseChartData'));
            $twig->addGlobal('getIncomeChartData', \App\Models\Balance::getChartData('incomeChartData'));

        }
        
        return $twig->render($template, $args);
    }
    
}