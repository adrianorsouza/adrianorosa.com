#!/usr/bin/php
<?php

ini_set('default_charset', "utf-8");
date_default_timezone_set('America/Sao_Paulo');

if(!defined("STDIN")) {
    define('DEBUG', true);
}

class PostWritter extends FileHandle
{
    /**
     * Define default path to the site sources
     *
     * @var string
     */
    public $path_draft    = '_drafts';
    public $path_post     = '_posts';
    public $path_source   = '_source';
    public $path_data     = '_source/_data';
    public $path_category = '_source/blog/category';
    public $path_template = '_source/resources/template';

    /**
     * store a JSON data read from categories.json
     *
     * @var array
     */
    protected $_category_data = array();

    /**
     * Sets category `name`, `slug` and `lead` values
     *
     * @var array
     */
    public $category = array();

    /**
     * Sets post attributes
     *
     * @var array
     */
    public $post = array();

    /**
     * Path to the prefered markdown editor
     *
     * @var string
     */
    public $editor = '/Applications/Mou.app';

    /**
     * Read Data Category from a JSON File
     *
     * @date 2014-08-16 23:41
     * @author Adriano Rosa (http://adrianorosa.com)
     *
     * @return void
     */
    protected function getJsonDataCategory()
    {
        $json_path = $this->path_data . DIRECTORY_SEPARATOR . 'categories.json';

        if ( !file_exists($json_path) ) {
            throw new PostException("Error Processing Request", 1);
        }

        $json_data = file_get_contents($json_path);
        $json_data = json_decode($json_data, true);

        if ( !is_array($json_data) ) {

            throw new PostException(
                sprintf('Invalid JSON format --> %s', $json_path)
                );

            // $this->setVerbose('error', sprintf('Invalid JSON format --> %s', $json_path));
            // echo $this->getVerbose();
            // exit(1);

        } else {

            $this->_category_data = $json_data;
        }

        return $this;
    }

    /**
     * Check whether category is in database.json
     *
     * @date 2014-08-16
     * @author Adriano Rosa (http://adrianorosa.com)
     *
     * @param string $category_name The Category name to check
     * @return bool
     */
    protected function isCategory($label = NULL)
    {
        if ( !$this->_category_data ) {
            $this->getJsonDataCategory();
        }

        foreach ($this->_category_data as $key => $value) {

            if ( in_array($label, array_values($value)) ) {

                return true;
            }
        }

        return false;
    }

    protected function getCategoryTemplate($data)
    {
        $template_category = file_get_contents($this->path_template . DIRECTORY_SEPARATOR . 'template_category.md');
        return vsprintf($template_category, $data);
    }

    /**
     * Update Data Category
     *
     * @return void
     */
    protected function updateCategory()
    {
        // skip whether category is not set
        if ( !$this->category ) {
            return;
        }

        $category_path = array_pop($this->category);

        // Update JSON for categories data whether category is not in the list

            $this->_category_data[] = $this->category;

            asort($this->_category_data);

            $jsonCategory = '';
            $jsonWrapper = "[\n%s\n]";

            foreach ($this->_category_data as $key => $value) {
                $jsonCategory .= json_encode($value, JSON_PRETTY_PRINT) . ",\n";
            }

            $jsonCategory = sprintf($jsonWrapper, rtrim($jsonCategory, ",\n"));

            $this->writeFile($this->path_data . DIRECTORY_SEPARATOR . 'categories.json', $jsonCategory);

        // Update category file
        if ( !file_exists( $category_path ) ) {

            // Load template categoriery
            $content = $this->getCategoryTemplate($this->category);
            $this->writeFile($category_path, $content);
        }
    }

    /**
     * Set post category whether category does not exists
     * it will attempt to create it and append to the database.json
     *
     * @date 2014-08-17 00:49
     * @author Adriano Rosa (http://adrianorosa.com)
     *
     * @param string $category_name
     * @return object
     */
    public function setCategory($category_name = NULL, $lead = '')
    {
        $this->category['label'] = $category_name;
        $this->category['slug'] = $this->_slugfy($category_name);
        $this->category['lead'] = $lead;
        $this->category['path'] = $this->path_category . DIRECTORY_SEPARATOR . $this->_slugfy($category_name) . '.md';

        return $this;
    }

    public function setPost($post_name = NULL)
    {
        $this->post['name'] = $post_name;
        $this->post['slug'] = $this->_slugfy($post_name);
        $this->post['path'] = $this->path_draft . DIRECTORY_SEPARATOR . $this->_slugfy($post_name, date('Y-m-d')) . '.md';

        return $this;
    }

    protected function getPostTemplate($data)
    {
        extract($data, EXTR_PREFIX_ALL, 'post');

        $template_post = file_get_contents($this->path_template . DIRECTORY_SEPARATOR . 'template_post.md');

        return sprintf($template_post, $post_name, $post_label, $post_slug);
    }

    protected function isPost()
    {
        return file_exists( $this->post['path'] );
    }

    /**
     * Add new post
     *
     * @date 2014-08-17 15:08
     * @author Adriano Rosa (http://adrianorosa.com)
     *
     * @return void
     */
    public function addPost()
    {
        $post_name = $this->readline('Post Name');
        $category = $this->readline('Category Name');

        if ( !$this->isCategory($category) ) {
            // Whether Category does not exists or attempt to create it
            $category_lead = $this->readline('Category description');
            $this->setCategory($category, $category_lead);
            $this->updateCategory();
        } else {
            $this->setCategory($category);
        }

        if ( !$post_name OR !$category ) {
            return false;
        }

        $this->setPost($post_name);

        // Whether Post already exists we attempt to edit it
        if ( $this->isPost() ) {
            $this->openFile($this->post['path'], $this->editor);
            return;
        }

        $data = array_merge($this->post, $this->category);

        // Load template
        $content = $this->getPostTemplate($data);
        $this->writeFile($this->post['path'], $content);

        $this->openFile($this->post['path'], $this->editor);

        echo $this->getVerbose();
    }

    private function readline($prompt = NULL)
    {
        if ( $prompt !== NULL ) {

            $line = readline("→ {$prompt}: ");
        }

        return $line;
    }

    /**
     * Format slug for Category and Posts
     *
     * @date 2014-08-17 17:20
     * @author Adriano Rosa (http://adrianorosa.com)
     *
     * @param string $string The title to be formatted into slug
     * @return string
     */
    private function _slugfy($string = null, $prep = NULL)
    {
        $string = remove_accents($string);
        $prep = ($prep) ? $prep . '-' : '';

        return preg_replace('/\s/', '-', $prep . trim( mb_strtolower($string) ));
    }
}
// ================================================================
try {

    $test = new PostWritter();
    $test->addPost();

} catch (PostException $e) {
    echo $e->errorMessage();
}
// ================================================================

/**
* Class FileHandle
*/
class FileHandle
{
    private $_result = array();

    /**
     * Write Files
     *
     * @param string
     * @return void
     */
    public function writeFile($filename, $content)
    {
        if ( !file_exists($filename) ) {

            $message = "Created a new file --> %s";

        } else {

            $message = "Updated data to a file --> %s";
        }

        if ( ! $handle = @fopen($filename, 'w')) {
            return $this->setVerbose('error', sprintf("Unable to read a file --> %s", $filename));
            // throw new PostException(
                    // sprintf("Unable to read a file: Check File Permissions --> %s", $filename)
                // );
        }

        flock($handle, LOCK_EX);

        if ( fwrite($handle, $content) === FALSE ) {
            // throw new PostException(sprintf("Unable to create a file --> %s", $filename));
            return $this->setVerbose('error', sprintf("Unable to create a file --> %s", $filename));
        }

        flock($handle, LOCK_UN);
        fclose($handle);

        $this->setVerbose('success', sprintf($message, $filename));

        return $this;
    }

    public function openFile($filename, $editor = NULL)
    {
        if ( !file_exists($filename) ) {
            $this->setVerbose('error', sprintf('File %s not found', $filename));
            return false;
        }

        if ( $editor !== NULL ) {
            $editor = '-a ' . $editor;
        }

        // exec command
        return exec("open {$filename} {$editor}");
        // exit(1);
    }

    public function is_cli()
    {

        return (PHP_SAPI === 'cli' OR defined('STDIN'));
    }

    public function setVerbose($type = 'error', $string = null)
    {
        if ( $type === 'success' ) {

            $message = sprintf("✔ Success %s", $string);

        } else {

            $message = sprintf("✗ Error %s", $string);
        }

        if ( $this->is_cli() ) {

            $pretty = ($type === 'success')
                ? "\033[0;32m"
                : "\033[0;31m";

            $message = $pretty . $message . "\033[0m" . PHP_EOL;

        } else {

            $pretty = ($type === 'success')
                ? '<span style="color:green">'
                : '<span style="color:red">';

            $message = $pretty . $message . '</span><br>' . PHP_EOL;
        }

        // return $message;
        $this->_result[][$type] = $message;
        // return $this;
    }

    public function getVerbose()
    {
        $count = 0;
        echo (!$this->is_cli()) ? "<pre>" : "\n";

        foreach ($this->_result as $result) {

            if ( isset($result['success']) ) {
                // $count++;
                echo $result['success'];

            } elseif ( isset($result['error']) ) {

                $count --;
                echo $result['error'];
            }

            $count++;
        }

        echo "------------------------------". PHP_EOL;
        echo ($count < 0) ? abs($count) . ' Errors found' : $count . ' Itens has been updated';
        echo PHP_EOL;
        echo "------------------------------". PHP_EOL;
        // exit(1);
    }
}

/**
* Error Handle
*/
class PostException extends \Exception
{
    /**
     * Prettify error message output
     * @return string
     */
    public function errorMessage()
    {
        $errorMsg = $this->getMessage() . "\n";
        return $errorMsg;
    }
}


function _vd($str, $exit = false){if (  !$exit ) {var_dump($str); exit();} var_dump($str);}
function _pr ($str){echo "<pre>"; print_r($str); exit();}

/**
 * Converts all accent characters to ASCII characters.
 *
 * If there are no accent characters, then the string given is just returned.
 *
 * @param string $string Text that might have accent characters
 * @return string Filtered string with replaced "nice" characters.
 */
function remove_accents($string) {
    if ( !preg_match('/[\x80-\xff]/', $string) ) {
        return $string;
    }

    if (seems_utf8($string)) {
        $chars = array(
            // Decompositions for Latin-1 Supplement
            chr(194).chr(170) => 'a', chr(194).chr(186) => 'o',
            chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
            chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
            chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
            chr(195).chr(134) => 'AE',chr(195).chr(135) => 'C',
            chr(195).chr(136) => 'E', chr(195).chr(137) => 'E',
            chr(195).chr(138) => 'E', chr(195).chr(139) => 'E',
            chr(195).chr(140) => 'I', chr(195).chr(141) => 'I',
            chr(195).chr(142) => 'I', chr(195).chr(143) => 'I',
            chr(195).chr(144) => 'D', chr(195).chr(145) => 'N',
            chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
            chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
            chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
            chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
            chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
            chr(195).chr(158) => 'TH',chr(195).chr(159) => 's',
            chr(195).chr(160) => 'a', chr(195).chr(161) => 'a',
            chr(195).chr(162) => 'a', chr(195).chr(163) => 'a',
            chr(195).chr(164) => 'a', chr(195).chr(165) => 'a',
            chr(195).chr(166) => 'ae',chr(195).chr(167) => 'c',
            chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
            chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
            chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
            chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
            chr(195).chr(176) => 'd', chr(195).chr(177) => 'n',
            chr(195).chr(178) => 'o', chr(195).chr(179) => 'o',
            chr(195).chr(180) => 'o', chr(195).chr(181) => 'o',
            chr(195).chr(182) => 'o', chr(195).chr(184) => 'o',
            chr(195).chr(185) => 'u', chr(195).chr(186) => 'u',
            chr(195).chr(187) => 'u', chr(195).chr(188) => 'u',
            chr(195).chr(189) => 'y', chr(195).chr(190) => 'th',
            chr(195).chr(191) => 'y', chr(195).chr(152) => 'O',
            // Decompositions for Latin Extended-A
            chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
            chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
            chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
            chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
            chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
            chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
            chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
            chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
            chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
            chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
            chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
            chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
            chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
            chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
            chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
            chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
            chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
            chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
            chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
            chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
            chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
            chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
            chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
            chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
            chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
            chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
            chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
            chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
            chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
            chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
            chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
            chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
            chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
            chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
            chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
            chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
            chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
            chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
            chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
            chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
            chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
            chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
            chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
            chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
            chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
            chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
            chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
            chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
            chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
            chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
            chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
            chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
            chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
            chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
            chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
            chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
            chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
            chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
            chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
            chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
            chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
            chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
            chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
            chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
            // Decompositions for Latin Extended-B
            chr(200).chr(152) => 'S', chr(200).chr(153) => 's',
            chr(200).chr(154) => 'T', chr(200).chr(155) => 't',
            // Euro Sign
            chr(226).chr(130).chr(172) => 'E',
            // GBP (Pound) Sign
            chr(194).chr(163) => '',
            // Vowels with diacritic (Vietnamese)
            // unmarked
            chr(198).chr(160) => 'O', chr(198).chr(161) => 'o',
            chr(198).chr(175) => 'U', chr(198).chr(176) => 'u',
            // grave accent
            chr(225).chr(186).chr(166) => 'A', chr(225).chr(186).chr(167) => 'a',
            chr(225).chr(186).chr(176) => 'A', chr(225).chr(186).chr(177) => 'a',
            chr(225).chr(187).chr(128) => 'E', chr(225).chr(187).chr(129) => 'e',
            chr(225).chr(187).chr(146) => 'O', chr(225).chr(187).chr(147) => 'o',
            chr(225).chr(187).chr(156) => 'O', chr(225).chr(187).chr(157) => 'o',
            chr(225).chr(187).chr(170) => 'U', chr(225).chr(187).chr(171) => 'u',
            chr(225).chr(187).chr(178) => 'Y', chr(225).chr(187).chr(179) => 'y',
            // hook
            chr(225).chr(186).chr(162) => 'A', chr(225).chr(186).chr(163) => 'a',
            chr(225).chr(186).chr(168) => 'A', chr(225).chr(186).chr(169) => 'a',
            chr(225).chr(186).chr(178) => 'A', chr(225).chr(186).chr(179) => 'a',
            chr(225).chr(186).chr(186) => 'E', chr(225).chr(186).chr(187) => 'e',
            chr(225).chr(187).chr(130) => 'E', chr(225).chr(187).chr(131) => 'e',
            chr(225).chr(187).chr(136) => 'I', chr(225).chr(187).chr(137) => 'i',
            chr(225).chr(187).chr(142) => 'O', chr(225).chr(187).chr(143) => 'o',
            chr(225).chr(187).chr(148) => 'O', chr(225).chr(187).chr(149) => 'o',
            chr(225).chr(187).chr(158) => 'O', chr(225).chr(187).chr(159) => 'o',
            chr(225).chr(187).chr(166) => 'U', chr(225).chr(187).chr(167) => 'u',
            chr(225).chr(187).chr(172) => 'U', chr(225).chr(187).chr(173) => 'u',
            chr(225).chr(187).chr(182) => 'Y', chr(225).chr(187).chr(183) => 'y',
            // tilde
            chr(225).chr(186).chr(170) => 'A', chr(225).chr(186).chr(171) => 'a',
            chr(225).chr(186).chr(180) => 'A', chr(225).chr(186).chr(181) => 'a',
            chr(225).chr(186).chr(188) => 'E', chr(225).chr(186).chr(189) => 'e',
            chr(225).chr(187).chr(132) => 'E', chr(225).chr(187).chr(133) => 'e',
            chr(225).chr(187).chr(150) => 'O', chr(225).chr(187).chr(151) => 'o',
            chr(225).chr(187).chr(160) => 'O', chr(225).chr(187).chr(161) => 'o',
            chr(225).chr(187).chr(174) => 'U', chr(225).chr(187).chr(175) => 'u',
            chr(225).chr(187).chr(184) => 'Y', chr(225).chr(187).chr(185) => 'y',
            // acute accent
            chr(225).chr(186).chr(164) => 'A', chr(225).chr(186).chr(165) => 'a',
            chr(225).chr(186).chr(174) => 'A', chr(225).chr(186).chr(175) => 'a',
            chr(225).chr(186).chr(190) => 'E', chr(225).chr(186).chr(191) => 'e',
            chr(225).chr(187).chr(144) => 'O', chr(225).chr(187).chr(145) => 'o',
            chr(225).chr(187).chr(154) => 'O', chr(225).chr(187).chr(155) => 'o',
            chr(225).chr(187).chr(168) => 'U', chr(225).chr(187).chr(169) => 'u',
            // dot below
            chr(225).chr(186).chr(160) => 'A', chr(225).chr(186).chr(161) => 'a',
            chr(225).chr(186).chr(172) => 'A', chr(225).chr(186).chr(173) => 'a',
            chr(225).chr(186).chr(182) => 'A', chr(225).chr(186).chr(183) => 'a',
            chr(225).chr(186).chr(184) => 'E', chr(225).chr(186).chr(185) => 'e',
            chr(225).chr(187).chr(134) => 'E', chr(225).chr(187).chr(135) => 'e',
            chr(225).chr(187).chr(138) => 'I', chr(225).chr(187).chr(139) => 'i',
            chr(225).chr(187).chr(140) => 'O', chr(225).chr(187).chr(141) => 'o',
            chr(225).chr(187).chr(152) => 'O', chr(225).chr(187).chr(153) => 'o',
            chr(225).chr(187).chr(162) => 'O', chr(225).chr(187).chr(163) => 'o',
            chr(225).chr(187).chr(164) => 'U', chr(225).chr(187).chr(165) => 'u',
            chr(225).chr(187).chr(176) => 'U', chr(225).chr(187).chr(177) => 'u',
            chr(225).chr(187).chr(180) => 'Y', chr(225).chr(187).chr(181) => 'y',
            // Vowels with diacritic (Chinese, Hanyu Pinyin)
            chr(201).chr(145) => 'a',
            // macron
            chr(199).chr(149) => 'U', chr(199).chr(150) => 'u',
            // acute accent
            chr(199).chr(151) => 'U', chr(199).chr(152) => 'u',
            // caron
            chr(199).chr(141) => 'A', chr(199).chr(142) => 'a',
            chr(199).chr(143) => 'I', chr(199).chr(144) => 'i',
            chr(199).chr(145) => 'O', chr(199).chr(146) => 'o',
            chr(199).chr(147) => 'U', chr(199).chr(148) => 'u',
            chr(199).chr(153) => 'U', chr(199).chr(154) => 'u',
            // grave accent
            chr(199).chr(155) => 'U', chr(199).chr(156) => 'u',
            );

        $string = strtr($string, $chars);
    } else {
      // Assume ISO-8859-1 if not UTF-8
        $chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
        .chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
        .chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
        .chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
        .chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
        .chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
        .chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
        .chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
        .chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
        .chr(252).chr(253).chr(255);

        $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

        $string = strtr($string, $chars['in'], $chars['out']);
        $double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
        $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
        $string = str_replace($double_chars['in'], $double_chars['out'], $string);
    }

    return $string;
}

/**
 * Checks to see if a string is utf8 encoded.
 *
 * NOTE: This function checks for 5-Byte sequences, UTF8
 *       has Bytes Sequences with a maximum length of 4.
 *
 * @author bmorel at ssi dot fr (modified)
 *
 * @param string $str The string to be checked
 * @return bool True if $str fits a UTF-8 model, false otherwise.
 */
function seems_utf8($str) {
    $length = strlen($str);
    for ($i=0; $i < $length; $i++) {
        $c = ord($str[$i]);
        if ($c < 0x80) $n = 0; # 0bbbbbbb
        elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
        elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
        elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
        elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
        elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
        else return false; # Does not match any model
        for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
            if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
                return false;
        }
    }
    return true;
}
