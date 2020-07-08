<?php


namespace App\Assist;


class Pager
{
    /**
     * Pager Widget for bootstrap scaffolding
     * @param $total - items quantity
     * @param $limit - chunk for render
     * @param $active - number of active page
     * @param string $url - additional path for link to Get Method
     * @return array - label for button, urn for link, class for button's css
     */
    public static function widget($total, $limit, $active, $url = '') {

        $additionalPage = $total % $limit;
        $amount = ($total - ($additionalPage)) / $limit;
        if($additionalPage) $amount++;

        if($active == 0) $active = 1;
        $res = [];
        if($amount <= 1) return $res;  // Empty it is one page
        $p = $url . '?page=';
        $res[] = ['label' => '<', 'urn' => $p . ($active - 1), 'class' => ($active == 1 ? 'disabled' : '')];
        if($amount < 6) {
            for ($i = 1; $i <= $amount; $i++)
                $res[] = ['label' => $i, 'urn' => $p . $i, 'class' => ($active == $i ? 'active' : '')];
        } else {
            if ($active < 4) {
                $res[] = ['label' => 1, 'urn' => $p . 1, 'class' => ($active == 1 ? 'active' : '')];
                $res[] = ['label' => 2, 'urn' => $p . 2, 'class' => ($active == 2 ? 'active' : '')];
                $res[] = ['label' => 3, 'urn' => $p . 3, 'class' => ($active == 3 ? 'active' : '')];
                $res[] = ['label' => '...', 'urn' => '', 'class' => 'disabled'];
                $res[] = ['label' => $amount, 'urn' => $p . $amount, 'class' => ($active == $amount ? 'active' : '')];
            } elseif (($amount - $active) < 3) {
                $res[] = ['label' => 1, 'urn' => $p . 1, 'class' => ($active == 1 ? 'active' : '')];
                $res[] = ['label' => '...', 'urn' => '', 'class' => 'disabled'];
                $res[] = ['label' => ($amount - 2), 'urn' => $p . ($amount - 2), 'class' => ($active == ($amount - 2) ? 'active' : '')];
                $res[] = ['label' => ($amount - 1), 'urn' => $p . ($amount - 1), 'class' => ($active == ($amount - 1) ? 'active' : '')];
                $res[] = ['label' => ($amount), 'urn' => $p . ($amount), 'class' => ($active == $amount ? 'active' : '')];
            } else {
                $res[] = ['label' => 1, 'urn' => $p . 1, 'class' => ($active == 1 ? 'active' : '')];
                $res[] = ['label' => '...', 'urn' => '',  'class' => 'disabled'];
                $res[] = ['label' => $active, 'urn' => $p . $active, 'class' => 'active'];
                $res[] = ['label' => '...', 'urn' => '',  'class' => 'disabled'];
                $res[] = ['label' => $amount, 'urn' => $p . $amount, 'class' => ($active == $amount ? 'active' : '')];
            }
        }
        $res[] = ['label' => '>', 'urn' => $p . ($active + 1), 'class' => ($active == $amount ? 'disabled' : '')];
        return $res;
    }

}