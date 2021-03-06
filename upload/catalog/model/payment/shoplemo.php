<?php

class ModelPaymentShoplemo extends Model
{
    public function getMethod($address, $total)
    {
        $this->load->language('payment/shoplemo');

        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $this->config->get('shoplemo_geo_zone_id') . "' AND country_id = '" . (int) $address['country_id'] . "' AND (zone_id = '" . (int) $address['zone_id'] . "' OR zone_id = '0')");

        if ($this->config->get('shoplemo_minimum_cart_total') > 0 && $this->config->get('shoplemo_minimum_cart_total') > $total)
        {
            $status = false;
        }
        elseif (!$this->config->get('shoplemo_geo_zone_id') || is_null($this->config->get('shoplemo_geo_zone_id')) || $this->config->get('shoplemo_geo_zone_id') == 0)
        {
            $status = true;
        }
        elseif ($query->num_rows)
        {
            $status = true;
        }
        else
        {
            $status = false;
        }

        $method_data = [];

        if ($status)
        {
            $method_data = [
                'code' => 'shoplemo',
                'title' => $this->language->get('text_title'),
                'terms' => '',
                'sort_order' => $this->config->get('shoplemo_sort_order'),
            ];
        }

        return $method_data;
    }
}
