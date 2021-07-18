<?php
namespace Umardevid\Escpos;

class Columnify {
    /**
     * @var string $separator
     *  separator of text
     * */
    public static $separator;
    /**
     * @var int $space
     *  space between column
     * */
    public static $space;
    /**
     * @var int $qtyWidth
     * width for the longest quantity
     * */
    public static  $qtyWidth;
    /**
     * @var int $priceWidth
     * width for the longest price
     * */
    public static  $priceWidth;
    /**
     * @var int $charWidth
     * Width of all Character sould be
     */
    public static  $charWidth;

    /**
     * Construct a new Columnify
     * @param int $qtyWidth the longest of quantity length
     * @param int $priceWidth width of longest price length
     * @param int $space space between column default to 2
     * @param string $separator separator character default to 32 characters
     * */
    public function __construct(int $qtyWidth, int $priceWidth, int $space = 2, string $separator, int $charWidth = 32)
    {
        /* Set the longest quantyty */
        self::$qtyWidth = $qtyWidth;

        /* Set longest price width */
        self::$priceWidth = $priceWidth;

        /* Set space */
        self::$space = $space;

        /* Set separator */
        self::$separator = str_repeat($separator, $charWidth);

        /* Set characters width */
        self::$charWidth = $charWidth;
    }

    /**
     * Columify product string
     * @param int $qty quantity for the product
     * @param string $name the product name
     * @param int $price price of the product
     * */
    public static function columnifyProduct(int $qty, string $name, int $price, int $disc = 0)
    {
        /* Remove space from begining and the end of the name */
        $name = trim($name);

        /* Convert int $price to idr */
        $price = number_format(num: $price, thousands_separator: ".");

        /**
         * Get quantity length
         * the fist we converting int $qty to string
         * */
        $qtyLen = strlen((string) $qty);

        /* Get name length */
        $nameLen = strlen($name);

        /**
         * Get price length
         * the fist we converting int $price to string
         * */
        $priceLen = strlen((string) $price);

        /* count separator */
        $separatorCount = 0;
        if (self::$priceWidth % 3 != 0) {
            $separatorCount = floor(self::$priceWidth / 3);
        } else {
            $separatorCount =  floor(self::$priceWidth / 3) - 1;
        }

        /* bigest price */
        $bigestPrice = self::$priceWidth + $separatorCount;
        // echo "Bigest: ". $bigestPrice . "\n";

        // echo self::$priceWidth % 3 . "\n";

        /* Get all characters */
        // echo "thousands_separator width: " . (self::$priceWidth / 3) . "\n";
        // echo "qty: " . self::$qtyWidth . "\n";
        // echo "space: " . self::$space * 2 . "\n";
        // echo "price: " . $bigestPrice . "\n";
        $allChars = self::$qtyWidth + $nameLen + $bigestPrice + (self::$space * 2);

        /* Name length sould be */
        $nameMustLen = null;

        /* Total chars to removed to make text same line to the first */
        $nameNeedRemoved = 0;

        /* Total name needed spaces */
        $nameNeedSpaces = 0;

        /**
         * if $allChars greather than $charWidth
         * then we set the width for name
         * */
        if ($allChars > self::$charWidth) {
            $moreChars = $allChars - self::$charWidth;
            // echo "more: " . $moreChars . "\n";
            
            /* set length of the name */
            $nameMustLen = $nameLen - $moreChars;
            // echo "all: " . $allChars . "\n";
            // echo "cw: " . self::$charWidth . "\n";
            // echo "name len: " . $nameLen . "\n";
            // echo "must: " . $nameMustLen . "\n";
            // echo "name must: " . $nameMustLen . "\n";

            /* set name need spaces */
            $nameNeedSpaces = $bigestPrice - $priceLen;
            // echo "test\n";
            // echo "all: " . $nameMustLen ."\n";
            // echo "price: " . $priceLen ."\n";
            // echo "bigest price: " . $bigestPrice - $priceLen ."\n";
        } else {
            // echo self::$priceWidth;
            $priceWidthMix = self::$priceWidth + floor(self::$priceWidth / 3);
            // echo "name len: " . $nameLen . "\n";
            // echo "name chars removed: " . $priceWidthMix - $priceLen . "\n";
            // echo "mix: " . $priceWidthMix . "\n";

            // Set removed chars
            $nameNeedRemoved = $priceWidthMix - $priceLen;
            // echo "price mix: " . $nameNeedRemoved. "\n";

            /* Set name sould be width */
            $nameMustLen = $nameLen;

            // echo "all: " . $allChars . "\n";
            // echo "qty: " . $qtyLen . "\n";
            // echo "space: " . self::$space * 2 . "\n";
            // echo "price len: " . $priceLen . "\n";
            // // echo "need price space: " . $bigestPrice - $priceLen . "\n";
            // echo "biggg: " . $bigestPrice . "\n";
            // echo "name len: " . $nameLen . "\n\n";

            // echo "nees space: " . $bigestPrice - $priceLen . "\n";
            // echo "must: " . $nameMustLen;

            // echo "\n\n";
            $nameNeedSpaces = $bigestPrice - $priceLen + (self::$charWidth - $allChars);
            // echo "" $nameNeedSpaces . "\n";
        }

        // echo $nameNeedRemoved . "\n";

        /* Extra space for name to make price align right */
        $nameExtraSpaces = "";

        // echo "char: " . $allChars ."\n";
        if (self::$charWidth > $allChars) {
            /* Set extraspace to name string */
            $nameExtraSpaces = str_repeat(" ", $nameNeedSpaces);
        } else {
            // $nameExtraSpaces = str_repeat("*", 1);
            // $nameExtraSpaces = str_repeat(".", $nameNeedRemoved);
            $nameExtraSpaces = str_repeat(" ", $nameNeedSpaces);
        }

        /**
         * Size of name part divide by $nameMustLen
         */
        $nameSize = $nameMustLen > 0 ? ceil($nameLen / $nameMustLen) : 1;

        /* Temporary all lines of string */
        $allLines = array();
        
        /* Loop all name parts */
        for ($i = 0; $i < $nameSize; $i++) {
            /* Name part */
            $namePart = trim(substr($name, $i * $nameMustLen, $nameMustLen));
            // echo "total angka: " . strlen($namePart) . "\n";
            // echo "ok" . $nameMustLen . "\n";
            $namePart = str_pad($namePart, $nameMustLen, " ") . $nameExtraSpaces;

            /* Space part */
            $spacePart = str_repeat(" ", self::$space);

            /* Quantity spaces */
            $qtySpaces = str_repeat(" ", self::$space + self::$qtyWidth);

            /* Quantity part */
            $qtyPart = $i < 1 ? $qty : "";
            $qtyPart .= $i < 1 ? substr($qtySpaces, $qtyLen ) : $qtySpaces;

            /* Price part */
            $pricePart = $i < 1 ? $spacePart . $price : "";

            // $pricePart = $i == 0 ? $pricePart . "\n" :;

            $allLines[] = $qtyPart . $namePart . $pricePart . "\n";
        }

        // Discount string
        $discount = "";
        if($disc > 0) {
            $discount = "-" . $disc;
            $discLen = strlen($discount);
            /* discount chars available */
            $discLenAv = self::$charWidth - $discLen;

            /* set discount string */
            $discount = str_repeat(" ", $discLenAv) . $discount;
        };

        echo implode($allLines) . $discount;
        echo "\n";
        return implode($allLines)  . $discount;
    }

    /**
     * Columnify payment info
     * @param array $data the information data
     */
    public static function columnifyPayInfo(array $data)
    {
        /* Get the maximum price */
        $higestVal = max($data);

        /* to IDR */
        $higestValIDR = number_format(num: $higestVal, thousands_separator: ".");

        /* Count the length */
        $higestValLen = strlen((string) $higestValIDR);

        /* Get the maximum label string width */
        $higestKey = 0;
        foreach ($data as $key => $value) {
            if (strlen($key) > $higestKey) $higestKey = strlen($key);
        }

        /* Temporary */
        $allLines = array();

        /**
         * loop the data for added space between column
         * */
        foreach ($data as $key => $value) {
            /* make label is uppercase */
            $key = strtoupper($key);

            /* get width of key string */
            $keyLen = strlen($key);

            /* convert value to IDr */
            $value = number_format(num: $value, thousands_separator: ".");

            /** 
             * if higest key not equal to width of key
             * then we set more need spaces for the label
             * */
            if ($higestKey != $keyLen) {
                $needSpace = $higestKey - $keyLen;
                $key = str_repeat(" ", $needSpace) . $key;
            }

            $valLen = strlen((string) $value);
            /**
             * If higest space not equal to value width
             * the we added the spaces for it
             */
            if ($higestValLen != $valLen) {
                $needSpace = $higestValLen - $valLen;
                $value = str_repeat(" ", $needSpace) . $value;
            }

            /* combine all */
            $allString = $key . " " . $value;

            /* count string width */
            $allStringLen = strlen($allString);

            /**
             * if all string width less than max characters width
             * then added more spaces for string
             * */
            if ($allStringLen < self::$charWidth) {
                /* get needed spaces */
                $needSpace = self::$charWidth - $allStringLen;

                /* set all string with more spaces */
                $allString = str_repeat(" ", $needSpace) . $allString;
            }

            /* set temporary variable */
            $allLines[] = $allString . "\n";
        }

        echo implode($allLines);

        /* return the mix string */
        return implode($allLines);
    }
}
