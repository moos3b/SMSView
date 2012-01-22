<?php

/**
 * Create a DOCX file. Endnote and footnote example
 *
 * @category   Phpdocx
 * @package    examples
 * @subpackage intermediate
 * @copyright  Copyright (c) 2009-2011 Narcea Producciones Multimedia S.L.
 *             (http://www.2mdc.com)
 * @license    http://www.phpdocx.com/wp-content/themes/lightword/pro_license.php
 * @version    1.8
 * @link       http://www.phpdocx.com
 * @since      File available since Release 1.8
 */
require_once('../../classes/CreateDocx.inc');

$docx = new CreateDocx();

$docx->addEndnote(
    array(
        'textDocument' => 'Lorem ipsum dolor sit amet',
        'textEndNote' => 'endnote'
    )
);

$docx->addFootnote(
    array(
        'textDocument' => 'Lorem ipsum dolor sit amet',
        'textEndNote' => 'footnote'
    )
);

$docx->createDocx('example_endnote_footnote');
