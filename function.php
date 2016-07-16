<?php
class dplayer_class
{
    function parseCallback($post,$siteurl,$dmserver,$theme) {
        $pattern = self::get_shortcode_regex(array('dplayer'));
        preg_match_all("/$pattern/",$post,$matches);
        if (empty($matches[0])) {
            return $post."<i id=\"dpajax\" hidden=\"hidden\"></i>";
        }
        for ($i=0;$i<count($matches[3]);$i++) {
            if ($matches[1][$i] == '[' && $matches[6][$i] == ']') {
                if (empty($out)) {
                    $out = str_replace($matches[0][$i], substr($matches[0][$i], 1, -1), $post);
                } else {
                    $out = str_replace($matches[0][$i], substr($matches[0][$i], 1, -1), $out);
                }
            } else {
                $attr = htmlspecialchars_decode($matches[3][$i]);
                $atts = self::shortcode_parse_atts($attr);
                $id = md5($siteurl.$atts['url']);
                $result = array(
                    'url' => $atts['url'] ? $atts['url'] : '',
                    'pic' => $atts['pic'] ? $atts['pic'] : '');
                if (empty($result)) return '';
                if (empty($theme)) $theme = '#FADFA3';
                $data = array(
                    'id' => $id,
                    'autoplay' => false,
                    'theme' => $theme);
                $data['autoplay'] = ($atts['autoplay'] == 'true') ? true : false;
                $data['theme'] = $atts['theme'] ? $atts['theme'] : $theme;
                $data['loop'] = ($atts['loop'] == 'true') ? true : false;
                $data['lang'] = 'zh';
                $data['video'] = $result;
                $danmaku = array(
                    'id' => md5($id),
                    'token' => md5(md5($id) . date('YmdH', time())),
                    'api' => $dmserver,);
                $data['danmaku'] = ($atts['danmu'] != 'false') ? $danmaku : null;
                if (empty($dmserver)) $data['danmaku'] = null;
                $js = json_encode($data);
                $src = "<div id=\"player".$id."\" class=\"dplayer\"></div>";
                if (empty($out)) {
                    $out = str_replace($matches[0][$i], $src, $post);
                    $jssrc = "dPlayerOptions.push(".$js.");";
                } else {
                    $out = str_replace($matches[0][$i], $src, $out);
                    $jssrc .= "dPlayerOptions.push(".$js.");";
                }
            }
        }
        $out .= "<i id=\"dpajax\" hidden=\"hidden\">".$jssrc."</i>";
        return $out;
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
}
?>