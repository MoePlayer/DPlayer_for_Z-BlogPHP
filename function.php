<?php
class DPlayer_class
{
    function parseCallback($post, $config) {
        $siteurl = $config->siteurl; $js = '';
        $pattern = self::get_shortcode_regex(array('dplayer'));
        preg_match_all("/$pattern/", $post, $matches);
        if (empty($matches[0])) return $post . '<div id="dpajax" style="display:none;"></div>';
        for ($i=0; $i<count($matches[0]); $i++) {
            if ($matches[1][$i] == '[' and $matches[6][$i] == ']') {
                $dp["#dp#$i#"] = substr($matches[0][$i], 1, -1);
                $out = empty($out) ? self::str_replace_once($matches[0][$i], "#dp#$i#", $post) : $out = self::str_replace_once($matches[0][$i], "#dp#$i#", $out);
            } else {
                $atts = self::shortcode_parse_atts(self::str_replace_nbsp($matches[3][$i]));
                if (isset($atts['url'])) {
                    $data = array('id' => md5($siteurl.$atts['url']));
                    $data['hotkey'] = isset($atts['hotkey']) ? self::str2bool($atts['hotkey']) : (bool)$config->hotkey;
                    $data['screenshot'] = isset($atts['screenshot']) ? self::str2bool($atts['screenshot']) : (bool)$config->screenshot;
                    $data['loop'] = isset($atts['loop']) ? self::str2bool($atts['loop']) : (bool)$config->loop;
                    $data['autoplay'] = isset($atts['autoplay']) ? self::str2bool($atts['autoplay']) : (bool)$config->autoplay;
                    $data['theme'] = isset($atts['theme']) ? $atts['theme'] : $config->theme;
                    $data['lang'] = isset($atts['lang']) ? $atts['lang'] : $config->lang == '1' ? 'zh' : 'en';
                    $data['preload'] = isset($atts['preload']) ? $atts['preload'] : $config->preload == '1' ? 'metadata' : $config->preload == '2' ? 'none' : 'auto';
                    $data['video'] = array_filter(array(
                        'url' => isset($atts['url']) ? $atts['url'] : null,
                        'pic' => isset($atts['pic']) ? $atts['pic'] : null,
                        'type' => isset($atts['type']) ? $atts['type'] : 'auto'    ));
                    $data['danmaku'] = array_filter(array(
                        'id' => isset($atts['id']) ? $atts['id'] : md5($data['id']),
                        'api' => isset($atts['api']) ? $atts['api'] : $config->dmserver,
                        'token' => isset($atts['token']) ? $atts['token'] : md5($data['id'].time()),
                        'maximum' => isset($atts['maximum']) ? $atts['maximum'] : $config->maximum,
                        'addition' => isset($atts['addition']) ? explode('|', $atts['addition']) : null    ));
                    if (empty($data['danmaku']['api']) or !$config->danmaku) $data['danmaku'] = null;
                    if (isset($atts['danmu'])) if (!self::str2bool($atts['danmu'])) $data['danmaku'] = null;
                    $js .= "\nDPlayerOptions.push(".self::json_encode_pretty($data).");";
                    $out = empty($out) ?
                        self::str_replace_once($matches[0][$i], '<div id="dp'.$data['id'].'" class="dplayer"></div>', $post):
                        self::str_replace_once($matches[0][$i], '<div id="dp'.$data['id'].'" class="dplayer"></div>', $out);
                }
            }
        }
        $out .= "<div id=\"dpajax\" style=\"display:none;\">$js\n</div>";
        if (isset($dp)) foreach ($dp as $k => $v) $out = str_replace($k,$v,$out); return $out;
    }
    
    function json_encode_pretty($src) {
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            return json_encode($src, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        } else {
            $src = urldecode(json_encode(self::json_encode_unescaped($src)));
            $ret = ''; $pos = 0; $newline = "\n"; $prevchar = '';
            $length = strlen($src); $indent = '    '; $outofquotes = true;
            for ($i=0; $i<=$length; $i++) {
                $char = substr($src, $i, 1);
                if ($char=='"' && $prevchar!='\\') $outofquotes = !$outofquotes;
                elseif (($char=='}' || $char==']') && $outofquotes) {
                    $ret .= $newline; $pos --; for ($j=0; $j<$pos; $j++) $ret .= $indent; }
                $ret .= $char;
                if (($char==',' || $char=='{' || $char=='[') && $outofquotes) {  
                    $ret .= $newline; if ($char=='{' || $char=='[') $pos ++; for ($j=0; $j<$pos; $j++) $ret .= $indent; }
                $prevchar = $char;
            }
            return str_replace(array('":', '"0"', '"1"', '"2"', '"3"', '""'), array('": ', '0', '1', '2', '3', '0'), $ret);
        }   }
    function json_encode_unescaped($src) {
        if (is_array($src)) foreach ($src as $key => $val)
            $out[urlencode($key)] = self::json_encode_unescaped($val);
        else $out = urlencode($src); return $out;                                       }

    function shortcode_parse_atts($text) {
        $atts = array();
        $pattern = '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
        if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
            foreach ($match as $m) {
                if (!empty($m[1])) $atts[strtolower($m[1])] = stripcslashes($m[2]);
                elseif (!empty($m[3])) $atts[strtolower($m[3])] = stripcslashes($m[4]);
                elseif (!empty($m[5])) $atts[strtolower($m[5])] = stripcslashes($m[6]);
                elseif (isset($m[7]) && strlen($m[7])) $atts[] = stripcslashes($m[7]);
                elseif (isset($m[8])) $atts[] = stripcslashes($m[8]);                    }
            foreach ($atts as &$value) if (false !== strpos($value, '<')) if (1 !== preg_match('/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value)) $value = '';
        } else $atts = ltrim($text);
        return $atts;
    }

    function get_shortcode_regex($tagnames = null) {
        return '\\[(\\[?)('.join('|', array_map('preg_quote', $tagnames)).
        ')(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';     }
    
    function str2bool($str) { return $str == 'true' ? true : ($str == 'false' ? false : $str); }
    function str_replace_nbsp($str) { return strip_tags(htmlspecialchars_decode(str_replace('&nbsp;',' ',$str))); }
    function str_replace_once($n, $r, $h) { return ($p = strpos($h, $n)) === false ? $h : substr_replace($h, $r, $p, strlen($n)); }
}