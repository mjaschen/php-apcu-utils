<?php

declare(strict_types=1);

namespace MarcusJaschen\APCu;

class ApcuUtils
{
    /**
     * Calculates the fragmentation ratio for the APCu cache.
     *
     * The algorithm is borrowed from `apc.php` status script which
     * is included in the APCu source code
     *
     * @see https://github.com/krakjoe/apcu/blob/master/apc.php
     *
     * @return float
     */
    public static function getFragmentationRatio(): float
    {
        /** @var array{num_seg: int, seg_size: int, avail_mem: int, block_lists: array[]} $mem */
        $mem = apcu_sma_info();

        $mem_avail = $mem['avail_mem'];

        // Some code taken from the file apc.php by The PHP Group.
        $nseg = 0;
        $freeseg = 0;
        $fragsize = 0;
        $freetotal = 0;
        for ($i = 0; $i < $mem['num_seg']; $i++) {
            $ptr = 0;
            /** @var array{size: int, offset: int} $block */
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
            $freeseg = 0;
        }

        return (float)($fragsize / $mem_avail);
    }

    /**
     * Returns the amount of available (free) cache memory.
     *
     * @return int
     */
    public static function getMemoryAvailable(): int
    {
        /** @var array{num_seg: int, seg_size: int, avail_mem: int, block_lists: array[]} $mem */
        $mem = apcu_sma_info();

        return $mem['avail_mem'];
    }
}
