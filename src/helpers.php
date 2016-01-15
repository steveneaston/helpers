<?php

if (! function_exists('pr')) {
    /**
     * Dump the passed variable
     *
     * @param  mixed $variable
     * @return void
     */
    function pr($variable)
    {
        for ($i = 1; $i <= func_num_args(); $i++) {
            echo "<pre>============\n <br>";
            print_r($variable);
            echo "\n<br>============\n <br></pre>";
        }
    }
}

if (! function_exists('prd')) {
    /**
     * Dump the passed variable
     *
     * @param  mixed $var
     * @return void
     */
    function prd($variable)
    {
        array_map('pr', func_get_args());
        die(1);
    }
}

if (! function_exists('trim_if_string')) {
    /**
     * Trim if variable is a string, otherwise trim as an array
     *
     * @param  mixed $data
     * @return mixed
     */
    function trim_if_string($data)
    {
        if (is_array($data) or is_object($data)) {
            return trim_array($data);
        }

        return trim($data);
    }
}

if (! function_exists('trim_array')) {
    /**
     * Trim each item of an array
     *
     * @param  array  $data
     * @return [type]       [description]
     */
    function trim_array(array $data)
    {
        return array_map('trim_if_string', $data);
    }
}

if (! function_exists('class_uses_trait')) {
    /**
     * Check whether or not a class uses a particular trait
     *
     * @param  class $class
     * @param  string $trait
     * @return boolean
     */
    function class_uses_trait($class, $trait)
    {
        return in_array($trait, class_uses($class, false));
    }
}

if (! function_exists('str_logic')) {
    /**
     * Returns single, multiple or zero choice based on count
     *
     * @param  int $count
     * @param  string $single   e.g. A Biscuit
     * @param  string $multiple e.g. Some Biscuits
     * @param  string $none     e.g. No Biscuits
     * @return string
     */
    function str_logic($count, $single, $multiple, $none = null)
    {
        if ($none !== null && $count <= 0) {
            return $none;
        }

        return $count == 1 ? $single : $multiple;
    }
}

if (! function_exists('filename_only')) {
    /**
     * Iterates over a list of files with paths returning only the filename
     *
     * @param  string|array $files
     * @return array
     */
    function filename_only($files)
    {
        if (! is_array($files)) {
            $files = [$files];
        }
        return array_map(function ($path) {
                return pathinfo($path, PATHINFO_FILENAME) . '.' . pathinfo($path, PATHINFO_EXTENSION);
            }, $files);
    }
}

if (! function_exists('get_filename')) {
    /**
     * Get the filename from a full path
     *
     * @param  string $file
     * @return string
     */
    function get_filename($file)
    {
        $file = filename_only($file);
        return array_shift($file);
    }
}

if (! function_exists('has_memory')) {
    /**
     * Check to see if there is enough memory to process an image
     *
     * @param  string  $image
     * @return boolean
     */
    function has_memory($image)
    {
        $info      = getimagesize($image);
        $channels  = isset($info['channels']) ? $info['channels'] : 4;
        $bits      = isset($info['bits']) ? $info['bits'] : 8;
        $required  = $info[0] * $info[1] * ($bits / 8) * $channels;
        $available = memory_get_usage();

        return (($available - $required) > 1);
    }
}

if (! function_exists('get_max_filesize')) {
    /**
     * Get the maximum upload filesize
     *
     * @return int
     */
    function get_max_filesize()
    {
        $iniMax = strtolower(ini_get('upload_max_filesize'));

        if ('' === $iniMax) {
            return PHP_INT_MAX;
        }

        $max = ltrim($iniMax, '+');
        if (0 === strpos($max, '0x')) {
            $max = intval($max, 16);
        } elseif (0 === strpos($max, '0')) {
            $max = intval($max, 8);
        } else {
            $max = (int) $max;
        }

        switch (substr($iniMax, -1)) {
            case 't': $max *= 1024;
            case 'g': $max *= 1024;
            case 'm': $max *= 1024;
            case 'k': $max *= 1024;
        }

        return $max;
    }
}


if (! function_exists('max_filesize')) {
    /**
     * Get a formatted maximum upload filesize
     *
     * @param  string $format
     * @return string
     */
    function max_filesize($format = 'b')
    {
        $size = min(
            get_max_filesize(),
            filter_var(ini_get('post_max_size'), FILTER_SANITIZE_NUMBER_INT) * 1024 * 1024
        );

        switch ($format) {
            case 'gb':
                return $size / 1024 / 1024 / 1024;
            case 'mb':
                return $size / 1024 / 1024;
            case 'kb':
                return $size / 1024;
            default:
                return $size;
        }
    }
}


if (! function_exists('lister')) {
    /**
     * List an array of items
     *
     * e.g. [Steven, Rik, Robbie] => Steven, Rik and Robbie
     *
     * @param  array  $data
     * @param  string $separator
     * @param  string $last
     * @return string
     */
    function lister(array $data, $separator = ', ', $last = ' and ')
    {
        if (count($data) <= 1) {
            return array_shift($data);
        }

        $last_item = array_pop($data);
        $str = implode($separator, $data);
        $str .= $last . $last_item;

        return $str;
    }
}

if (! function_exists('str_float')) {
    /**
     * Return a float from a formatted string
     *
     * e.g. £1,234.52 => 1234.52
     *
     * @param  string $string
     * @return float
     */
    function str_float($string)
    {
        return (float) preg_replace('/[^0-9\.\-]/', '', $string);
    }
}

if (! function_exists('format_period')) {
    function format_period(DateTime $start, DateTime $end, $time = false)
    {
        // Clean up minutes
        // e.g. 19:00 = 7pm, 19:20 = 7:20pm
        $startMinuteFormat = $start->format('i') == '00' ? 'ga' : 'g:ia';
        $endMinuteFormat   = $end->format('i') == '00' ? 'ga' : 'g:ia';

        // Prepare the time formats if set
        $startTime = $time ? ' ' . $startMinuteFormat : '';
        $endTime   = $time ? ' ' . $endMinuteFormat : '';

        $formatted = '';
        $diff = $start->diff($end);
        $diffHours = $diff->h + ($diff->days * 24);

        // Date spans several days
        if ($diffHours >= 24) {

            // Date spans several years
            if ($start->format('Y') != $end->format('Y')) {
                $formatted = sprintf('%s &ndash; %s', $start->format('jS F Y' . $startTime), $end->format('jS F Y' . $endTime));

            //Date spans several months
            } elseif ($start->format('n') != $end->format('n')) {
                $formatted = sprintf('%s &ndash; %s', $start->format('jS F' . $startTime), $end->format('jS F Y' . $endTime));

            // Date spans single month
            } else {
                $formatted = sprintf('%s &ndash; %s', $start->format($time ? 'jS F' . $startTime : 'jS'), $end->format('jS F Y' . $endTime));
            }

        // Date spans a single day
        } else {
            $formatted = sprintf('%s &ndash; %s', $start->format('jS F Y ' . $startMinuteFormat), $end->format($endMinuteFormat));
        }

        return $formatted;
    }
}

if (! function_exists('set_not_empty')) {
    function set_not_empty(array $data, $attribute)
    {
        return isset($data[$attribute]) && ! empty($data[$attribute]);
    }
}

if (! function_exists('env')) {
    /**
     * Get an environmental variable.
     *
     * Based on Foundation helpers, part of laravel/framework
     * https://github.com/laravel/framework/
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return;
        }

        return $value;
    }
}

if ( ! function_exists('str_to_bool'))
{
    function str_to_bool($value)
    {
        if (is_bool($value)) return $value;
        if (! is_string($value)) return false;

        return strtolower($value) == 'true';
    }
}
