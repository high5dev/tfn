<?php

namespace App\Rules;

class CustomValidator
{
    /**
     * validates that password is strong enough
     *
     * @param   string  $attribute  name of the attribute being validated
     * @param   string  $value      value of the attribute being validated
     * @param   array   $parameters array of parameters passed to the rule
     * @param   object  $validator  an instance of the validator
     */
    public function validatePasswordStrength($attribute, $value, $parameters, $validator)
    {
        $minBits = 12;
        $useDict = true;
        $minWordLen = 4;
        return $this->isStrongPassword($value, $strength, $minBits, $useDict, $minWordLen);
    }

    /**
     * NIST algorithm for determining password strength
     *
     * @param   string  $password   the password to check
     * @param   integer $result     the number of bits found
     * @param   integer $minBits    minimum number of bits for an acceptable password
     * @param   boolean $useDict    whether to use the password dictionary or not
     * @param   integer $minWordLen minimum word length for phrases
     *
     * @return  boolean true if password passes our checks
     */
    public function isStrongPassword($password, &$result, $minBits = 18, $useDict = false, $minWordLen = 4)
    {
        // NIST password strength rules allow up to 6 extra bits for mixed case and non-alphabetic.^M
        $upper = false;
        $lower = false;
        $numeric = false;
        $other = false;
        $space = false;
        $y = strlen( $password );
        for ($x = 0; $x < $y; $x++) {
            $tempchr = ord( substr( $password, $x, 1 ) );
            if ($tempchr >= ord( "A" ) && $tempchr <= ord( "Z" )) {
                $upper = true;
            } else {
                if ($tempchr >= ord( "a" ) && $tempchr <= ord( "z" )) {
                    $lower = true;
                } else {
                    if ($tempchr >= ord( "0" ) && $tempchr <= ord( "9" )) {
                        $numeric = true;
                    } else {
                        if ($tempchr == ord( " " )) {
                            $space = true;
                        } else {
                            $other = true;
                        }
                    }
                }
            }
        }

        $extrabits = ($upper && $lower && $other ? ($numeric ? 6 : 5) : ($numeric && !$upper && !$lower ? ($other ? -2 : -6) : 0));
        if (!$space) {
            $extrabits -= 2;
        } else {
            if (count( explode( " ", preg_replace( '/\s+/', " ", $password ) ) ) > 3) {
                $extrabits++;
            }
        }
        $result = $this->GetNISTNumBits( $password, true ) + $extrabits;

        $password = strtolower( $password );
        $revpassword = strrev( $password );
        $numbits = $this->GetNISTNumBits( $password ) + $extrabits;
        if ($result > $numbits) {
            $result = intval( $numbits );
        }

        // Remove QWERTY strings.^M
        $qwertystrs = array (
            "1234567890-qwertyuiopasdfghjkl;zxcvbnm,./",
            "1qaz2wsx3edc4rfv5tgb6yhn7ujm8ik,9ol.0p;/-['=]:?_{\"+}",
            "1qaz2wsx3edc4rfv5tgb6yhn7ujm8ik9ol0p",
            "qazwsxedcrfvtgbyhnujmik,ol.p;/-['=]:?_{\"+}",
            "qazwsxedcrfvtgbyhnujmikolp",
            "]\"/=[;.-pl,0okm9ijn8uhb7ygv6tfc5rdx4esz3wa2q1",
            "pl0okm9ijn8uhb7ygv6tfc5rdx4esz3wa2q1",
            "]\"/[;.pl,okmijnuhbygvtfcrdxeszwaq",
            "plokmijnuhbygvtfcrdxeszwaq",
            "014725836914702583697894561230258/369*+-*/",
            "abcdefghijklmnopqrstuvwxyz"
            );

        foreach ($qwertystrs as $qwertystr) {
            $qpassword = $password;
            $qrevpassword = $revpassword;
            $z = 6;
            do {
                $y = strlen( $qwertystr ) - $z;
                for ($x = 0; $x < $y; $x++) {
                    $str = substr( $qwertystr, $x, $z );
                    $qpassword = str_replace( $str, "*", $qpassword );
                    $qrevpassword = str_replace( $str, "*", $qrevpassword );
                }

                $z--;
            } while ($z > 2);

            $numbits = $this->GetNISTNumBits( $qpassword ) + $extrabits;
            if ($result > $numbits) {
                $result = intval( $numbits );
            }
            $numbits = $this->GetNISTNumBits( $qrevpassword ) + $extrabits;
            if ($result > $numbits) {
                $result = intval( $numbits );
            }

            if ($result < $minBits) {
                return false;
            }
        }

        if ($useDict && $result >= $minBits) {
            $passwords = array ();

            // Add keyboard shifting password variants.^M
            $keyboardmap_down_noshift = array (
                "z" => "",
                "x" => "",
                "c" => "",
                "v" => "",
                "b" => "",
                "n" => "",
                "m" => "",
                "," => "",
                "." => "",
                "/" => "",
                "<" => "",
                ">" => "",
                "?" => ""
                );

            if ($password == str_replace( array_keys( $keyboardmap_down_noshift ), array_values( $keyboardmap_down_noshift ), $password )) {
                $keyboardmap_downright = array (
                    "a" => "z",
                    "q" => "a",
                    "1" => "q",
                    "s" => "x",
                    "w" => "s",
                    "2" => "w",
                    "d" => "c",
                    "e" => "d",
                    "3" => "e",
                    "f" => "v",
                    "r" => "f",
                    "4" => "r",
                    "g" => "b",
                    "t" => "g",
                    "5" => "t",
                    "h" => "n",
                    "y" => "h",
                    "6" => "y",
                    "j" => "m",
                    "u" => "j",
                    "7" => "u",
                    "i" => "k",
                    "8" => "i",
                    "o" => "l",
                    "9" => "o",
                    "0" => "p",
                    );

                $keyboardmap_downleft = array (
                    "2" => "q",
                    "w" => "a",
                    "3" => "w",
                    "s" => "z",
                    "e" => "s",
                    "4" => "e",
                    "d" => "x",
                    "r" => "d",
                    "5" => "r",
                    "f" => "c",
                    "t" => "f",
                    "6" => "t",
                    "g" => "v",
                    "y" => "g",
                    "7" => "y",
                    "h" => "b",
                    "u" => "h",
                    "8" => "u",
                    "j" => "n",
                    "i" => "j",
                    "9" => "i",
                    "k" => "m",
                    "o" => "k",
                    "0" => "o",
                    "p" => "l",
                    "-" => "p",
                    );

                $password2 = str_replace( array_keys( $keyboardmap_downright ), array_values( $keyboardmap_downright ), $password );
                $passwords[] = $password2;
                $passwords[] = strrev( $password2 );

                $password2 = str_replace( array_keys( $keyboardmap_downleft ), array_values( $keyboardmap_downleft ), $password );
                $passwords[] = $password2;
                $passwords[] = strrev( $password2 );
            }

            // Deal with LEET-Speak substitutions.^M
            $leetspeakmap = array (
                "@" => "a",
                "!" => "i",
                "$" => "s",
                "1" => "i",
                "2" => "z",
                "3" => "e",
                "4" => "a",
                "5" => "s",
                "6" => "g",
                "7" => "t",
                "8" => "b",
                "9" => "g",
                "0" => "o"
                );

            $password2 = str_replace( array_keys( $leetspeakmap ), array_values( $leetspeakmap ), $password );
            $passwords[] = $password2;
            $passwords[] = strrev( $password2 );

            $leetspeakmap[ "1" ] = "l";
            $password3 = str_replace( array_keys( $leetspeakmap ), array_values( $leetspeakmap ), $password );
            if ($password3 != $password2) {
                $passwords[] = $password3;
                $passwords[] = strrev( $password3 );
            }

            // Process the password, while looking for words in the dictionary.^M
            $a = ord( "a" );
            $z = ord( "z" );
            $data = file_get_contents( config('app.dictionary') );
            foreach ($passwords as $num => $password) {
                $y = strlen( $password );
                for ($x = 0; $x < $y; $x++) {
                    $tempchr = ord( substr( $password, $x, 1 ) );
                    if ($tempchr >= $a && $tempchr <= $z) {
                        for ($x2 = $x + 1; $x2 < $y; $x2++) {
                            $tempchr = ord( substr( $password, $x2, 1 ) );
                            if ($tempchr < $a || $tempchr > $z) {
                                break;
                            }
                        }

                        $found = false;
                        while (!$found && $x2 - $x >= $minWordLen) {
                            $word = "/\\n" . substr( $password, $x, $minWordLen );
                            for ($x3 = $x + $minWordLen; $x3 < $x2; $x3++) {
                                $word .= "(" . $password{ $x3 };
                            }
                            for ($x3 = $x + $minWordLen; $x3 < $x2; $x3++) {
                                $word .= ")?";
                            }
                            $word .= "\\n/";

                            preg_match_all( $word, $data, $matches );
                            if (!count( $matches[ 0 ] )) {
                                $password{ $x } = "*";
                                $x++;
                                $numbits = $this->GetNISTNumBits( substr( $password, 0, $x ) ) + $extrabits;
                                if ($numbits >= $minBits) {
                                    $found = true;
                                }
                            } else {
                                foreach ($matches[ 0 ] as $match) {
                                    $password2 = str_replace( trim( $match ), "*", $password );
                                    $numbits = $this->GetNISTNumBits( $password2 ) + $extrabits;
                                    if ($result > $numbits) {
                                        $result = intval( $numbits );
                                    }

                                    if ($result < $minBits) {
                                        return false;
                                    }
                                }

                                $found = true;
                            }
                        }

                        if ($found) {
                            break;
                        }

                        $x = $x2 - 1;
                    }
                }
            }
        }
        return $result >= $minBits;
    }


    /**
     * NIST password weighting algorithm
     *
     * @param   string  $password   the password to check
     * @param   boolean $repeat     true to iterate over each char
     *
     * @return  integer password length in bits
     */
    public function GetNISTNumBits($password, $repeat = false)
    {
        $y = strlen( $password );
        if ($repeat) {
            // Variant on NIST rules to reduce long sequences of repeated characters.^M
            $result = 0;
            $charmult = array_fill( 0, 256, 1 );
            for ($x = 0; $x < $y; $x++) {
                $tempchr = ord( substr( $password, $x, 1 ) );
                if ($x > 19) {
                    $result += $charmult[ $tempchr ];
                } else {
                    if ($x > 7) {
                        $result += $charmult[ $tempchr ] * 1.5;
                    } else {
                        if ($x > 0) {
                            $result += $charmult[ $tempchr ] * 2;
                        } else {
                            $result += 4;
                        }
                    }
                }
                $charmult[ $tempchr ] *= 0.75;
            }

            return intval( $result );
        } else {
            if ($y > 20)
                return 4 + (7 * 2) + (12 * 1.5) + $y - 20;
            if ($y > 8)
                return 4 + (7 * 2) + (($y - 8) * 1.5);
            if ($y > 1)
                return 4 + (($y - 1) * 2);

            return intval( $y == 1 ? 4 : 0 );
        }
    }

}