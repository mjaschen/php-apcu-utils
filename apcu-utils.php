<?php
declare(strict_types = 1);

/**
 * APCu Utils
 *
 * @author  Marcus Jaschen <mjaschen@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link    https://www.marcusjaschen.de/
 */

namespace MarcusJaschen\APCu;

/**
 * Calculates the fragmentation ratio for the APCu cache.
 *
 * The algorithm is borrowed from `apc.php` status script which
 * is included in the APCu source code
 *
 * @link https://github.com/krakjoe/apcu/blob/master/apc.php
 *
 * @return float
 */
function getFragmentationRatio(): float
{
    $mem = apcu_sma_info();

    $mem_size  = $mem['num_seg'] * $mem['seg_size'];
    $mem_avail = $mem['avail_mem'];

    // Some code taken from the file apc.php by The PHP Group.
    $nseg = $freeseg = $fragsize = $freetotal = 0;
    for ($i = 0; $i < $mem['num_seg']; $i++) {
        $ptr = 0;
        foreach ($mem['block_lists'][$i] as $block) {
            if ($block['offset'] !== $ptr) {
                ++$nseg;
            }
            $ptr = $block['offset'] + $block['size'];
            // Only consider blocks < 5M for the fragmentation %.
            if ($block['size'] < (5 * 1024 * 1024)) {
                $fragsize += $block['size'];
            }
            $freetotal += $block['size'];
        }
        $freeseg += count($mem['block_lists'][$i]);
    }

    if ($freeseg < 2) {
        $fragsize = 0;
        $freeseg  = 0;
    }

    return $fragsize / $mem_avail;
}
