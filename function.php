<?php
class dplayer_class
{
    function parseCallback($post,$config) {
        $pattern = self::get_shortcode_regex(array('dplayer'));
        preg_match_all("/$pattern/",$post,$matches);
        if (empty($matches[0])) {
            return $post."<i id=\"dpajax\" hidden=\"hidden\"></i>";
        }
        for ($i=0;$i<count($matches[0]);$i++) {
            if ($matches[1][$i] == '[' and $matches[6][$i] == ']') {
                $dp["#dp#$i#"] = substr($matches[0][$i], 1, -1);
                if (empty($out)) {
                    $out = self::str_replace_once($matches[0][$i], "#dp#$i#", $post);
                } else {
                    $out = self::str_replace_once($matches[0][$i], "#dp#$i#", $out);
                }
            } else {
                $attr = htmlspecialchars_decode($matches[3][$i]);
                $atts = self::shortcode_parse_atts($attr);
                if (isset($atts['url'])) {
                    $data = array('id' => md5($siteurl.$atts['url']));
                    if (isset($atts['hotkey'])) $data['hotkey']=self::str2bool($atts['hotkey']); else $data['hotkey']=(bool)$config['hotkey'];
                    if (isset($atts['screenshot'])) $data['screenshot']=self::str2bool($atts['screenshot']); else $data['screenshot']=(bool)$config['screenshot'];
                    if (isset($atts['loop'])) $data['loop']=self::str2bool($atts['loop']); else $data['loop']=(bool)$config['loop'];
                    if (isset($atts['autoplay'])) $data['autoplay']=self::str2bool($atts['autoplay']); else $data['autoplay']=(bool)$config['autoplay'];
                    if (isset($atts['theme'])) $data['theme']=$atts['theme']; else $data['theme']=$config['theme'];
                    if ($config['lang']==1) $data['lang'] = 'zh'; elseif ($config['lang']==2) $data['lang'] = 'en';
                    if ($config['preload']==1) $data['preload'] = 'metadata'; elseif ($config['preload']==2) $data['preload'] = 'none'; else $data['preload'] = 'auto';
                    if (isset($atts['lang'])) $data['lang'] = $atts['lang'];if (isset($atts['preload'])) $data['preload'] = $atts['preload'];
                    $data['video'] = array(
                        'url' => $atts['url'] ? $atts['url'] : '',
                        'pic' => $atts['pic'] ? $atts['pic'] : ''
                    );
                    if (isset($atts['id'])) $dmid = $atts['id']; else $dmid = md5($data['id']);
                    $data['danmaku'] = array(
                        'id' => $dmid,
                        'token' => md5($dmid.date('YmdH',time())),
                        'api' => $config['dmserver']
                    );
                    if (empty($config['dmserver']) or !$config['danmaku']) $data['danmaku'] = null;
                    if (isset($atts['danmu'])) if (!self::str2bool($atts['danmu'])) $data['danmaku'] = null;
                    if (empty($out)) {
                        $js = "DPlayerOptions.push(".json_encode($data).");";
                        $out = self::str_replace_once($matches[0][$i], "<div id=\"player".$data['id']."\" class=\"dplayer\"></div>", $post);
                    } else {
                        $js .= "DPlayerOptions.push(".json_encode($data).");";
                        $out = self::str_replace_once($matches[0][$i], "<div id=\"player".$data['id']."\" class=\"dplayer\"></div>", $out);
                    }
                }
            }
        }
        $out .= "<i id=\"dpajax\" hidden=\"hidden\">".$js."</i>";
        if (isset($dp)) foreach ($dp as $k => $v) return str_replace($k,$v,$out); else return $out;
    }

    function shortcode_parse_atts($text) {
        $atts = array();
        $pattern = '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
        if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
            foreach ($match as $m) {
                if (!empty($m[1]))
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                elseif (!empty($m[3]))
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                elseif (!empty($m[5]))
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                elseif (isset($m[7]) && strlen($m[7]))
                    $atts[] = stripcslashes($m[7]);
                elseif (isset($m[8]))
                    $atts[] = stripcslashes($m[8]);
                }
            foreach ($atts as &$value) {
                if (false !== strpos($value, '<')) {
                    if (1 !== preg_match('/^[^<]*+(?:<[^>]*+>[^<]*+)*+$/', $value)) {
                        $value = '';
                    }
                }
            }
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
        if ($pos === false) {
            return $haystack;
        }
        return substr_replace($haystack, $replace, $pos, strlen($needle));
    }
    
    function str2bool($str) {
        if ($str=='true') return true;elseif ($str=='false') return false;else return $str;
    }
}
?>