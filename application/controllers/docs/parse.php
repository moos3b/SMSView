<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Parse extends CI_Controller {
    /**
     *
     * This controller parses a template with an xml source
     */

     
     /**
     * Constructor, we load our default helpers here
     */
    public function __construct()
    {
        parent::__construct();
        setlocale(LC_ALL, 'nl_NL');
        require_once ('features/percentages.php');
        require_once ('features/scores.php');
        require_once ('features/satisfaction.php');
        require_once 'pro/classes/CreateDocx.inc';
        //load url helper
        $this -> load -> helper('url');
    }
    
    
    public function doc($template = false, $xml_source = false, $output_file = false) {
        $template = urldecode($template);
        $xml_source = urldecode($xml_source);
        $output_file = urldecode($output_file);
        if (!$template) {
            die("Geef een template op!\n");
        }
        if (!$xml_source) {
            die("Geef een xml source op!\n");
        }
        if (!$output_file) {
            die("Geef een uitvoer bestand op!\n");
        }
        echo "Building report with template: " . $template . " , xml source: " . $xml_source . " and output to: " . $output_file . "\n";

        $this -> load -> library('simplexml');

        $xmlRaw = file_get_contents($xml_source);

        $xmlData = $this -> simplexml -> xml_parse($xmlRaw);

        //$percentages = new percentages();
        //$percentage_docx = $percentages -> render($xmlData);
        //unset($percentages);
        
        //$scores = new scores();
        //$scores_docx = $scores -> render($xmlData);
        //unset($scores);
        
        $satisfaction = new satisfaction();
        $satisfaction_docx = $satisfaction -> render($xmlData);
        unset($satisfaction);
        
        $docx = new CreateDocx();

        $docx -> addTemplate($template);
        $variables = $docx -> getTemplateVariables();

        foreach ($variables['document'] as $template_variable) {
            print "got variable: " . $template_variable . "\n";
            $var = explode(":", $template_variable);
            $type = $var[0];
            $variable = $var[1];
            print "got variable type: " . $type . " for variable: " . $variable . "\n";
            if ($type == 'xml') {
                //get direct from xml
                $docx -> addTemplateVariable($template_variable, $xmlData[$variable]);
            } elseif ($type == "proc") {
                //process
                $docx -> addTemplateVariable('proc:datum', strftime("%e %B %Y"));
            } elseif ($type == "class") {
                //get class to process
                //scores and percentages
                //if ($variable = "percentages") {
                //    $docx -> addTemplateVariable('class:percentages', $percentage_docx, 'docx');
                //}
                //if ($variable = "scores") {
                //    $docx -> addTemplateVariable('class:scores', $scores_docx, 'docx');
                //}
                if ($variable = "satisfaction") {
                    $docx -> addTemplateVariable('class:satisfaction', $satisfaction_docx, 'docx');

                }
            }

        }
        $docx -> addText("Created by oqdoc " . strftime("%e %B %Y"));

        $docx -> createDocx($output_file);

        echo "Done\n";

    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
