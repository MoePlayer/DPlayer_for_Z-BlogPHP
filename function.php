<?php
class dplayer_class
{
    function parseCallback($post,$config) {
        preg_match_all('/'.self::get_shortcode_regex(array('dplayer')).'/', $post, $matches);
        if (empty($matches[0])) return $post . '<i id="dpajax" hidden="hidden"></i>';
        for ($i=0;$i<count($matches[0]);$i++) {
            if ($matches[1][$i] == '[' and $matches[6][$i] == ']') {
                $dp["#dp#$i#"] = substr($matches[0][$i], 1, -1);
                $out = empty($out) ? self::str_replace_once($matches[0][$i], "#dp#$i#", $post) : $out = self::str_replace_once($matches[0][$i], "#dp#$i#", $out);
            } else {
                $attr = htmlspecialchars_decode($matches[3][$i]);
                $atts = self::shortcode_parse_atts($attr);
                if (isset($atts['url'])) {
                    $data = array('id' => md5($siteurl.$atts['url']));
                    $data['hotkey'] = isset($atts['hotkey']) ? self::str2bool($atts['hotkey']) : (bool)$config['hotkey'];
                    $data['screenshot'] = isset($atts['screenshot']) ? self::str2bool($atts['screenshot']) : (bool)$config['screenshot'];
                    $data['loop'] = isset($atts['loop']) ? self::str2bool($atts['loop']) : (bool)$config['loop'];
                    $data['autoplay'] = isset($atts['autoplay']) ? self::str2bool($atts['autoplay']) : (bool)$config['autoplay'];
                    $data['theme'] = isset($atts['theme']) ? $atts['theme'] : $config['theme'];
                    $data['lang'] = isset($atts['lang']) ? $atts['lang'] : $config['lang']=='1' ? 'zh' : 'en';
                    $data['preload'] = isset($atts['preload']) ? $atts['preload'] : $config['preload']=='1' ? 'metadata' : $config['preload']=='2' ? 'none' : 'auto';
                    $data['video'] = array_filter(array(
                        'url' => $atts['url'] ? $atts['url'] : null,
                        'pic' => $atts['pic'] ? $atts['pic'] : null,
                        'type' => $atts['type'] ? $atts['type'] : 'auto'    ));
                    $data['danmaku'] = array_filter(array(
                        'id' => $atts['id'] ? $atts['id'] : md5($data['id']),
                        'api' => $atts['api'] ? $atts['api'] : $config['dmserver'],
                        'token' => $atts['token'] ? $atts['token'] : md5($data['id'].time()),
                        'maximum' => $atts['maximum'] ? $atts['maximum'] : $config['maximum'],
                        'addition' => $atts['addition'] ? explode('|',$atts['addition']) : null    ));
                    if (empty($config['dmserver']) or !$config['danmaku']) $data['danmaku'] = null;
                    if (isset($atts['danmu'])) if (!self::str2bool($atts['danmu'])) $data['danmaku'] = null;
                    $js .= "DPlayerOptions.push(".json_encode(array_filter($data, 'self::is_not_null')).");";
                    $out = empty($out) ?
                        self::str_replace_once($matches[0][$i], '<div id="dplayer-'.$data['id'].'" class="dplayer"></div>', $post):
                        self::str_replace_once($matches[0][$i], '<div id="dplayer-'.$data['id'].'" class="dplayer"></div>', $out);
                }
            }
        }
        $out .= '<i id="dpajax" hidden="hidden">'.$js.'</i>';
        if (isset($dp)) foreach ($dp as $k => $v) $out = str_replace($k,$v,$out); return $out;
    }

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
                elseif (isset($m[8])) $atts[] = stripcslashes($m[8]);
            }
            foreach ($atts as &$value) if (false !== strpos($value, '<')) if (1 !== preg_match('/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value)) $value = '';
        } else {
            $atts = ltrim($text);
        }
        return $atts;
    }

    function get_shortcode_regex($tagnames = null) {
        $tagregexp = join('|', array_map('preg_quote', $tagnames));
        return '\\['.'(\\[?)'. "($tagregexp)".'(?![\\w-])'.'('.'[^\\]\\/]*'.'(?:'.'\\/(?!\\])'.'[^\\]\\/]*'.')*?'.')'. '(?:'.'(\\/)'.'\\]'.'|'.'\\]'.'(?:'.'('.'[^\\[]*+'.'(?:'.'\\[(?!\\/\\2\\])'.'[^\\[]*+'.')*+'.')'.'\\[\\/\\2\\]'.')?'.')'.'(\\]?)';
    }
    
    function str_replace_once($needle, $replace, $haystack) {
        $pos = strpos($haystack, $needle);
        if ($pos === false) return $haystack;
        return substr_replace($haystack, $replace, $pos, strlen($needle));
    }
    
    function str2bool($str) {
        if ($str=='true') return true; elseif ($str=='false') return false; else return $str;
    }
    
    function is_not_null($val) { return !is_null($val); }
}
?>