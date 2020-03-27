<?php

namespace Tbruckmaier\Corcelacf\Models;

use Carbon\Carbon;

class DateTime extends BaseField
{
    public function getInternalFormatAttribute()
    {
        switch ($this->type) {
            case 'date_picker':
                return 'Ymd';
            case 'date_time_picker':
                return 'Y-m-d H:i:s';
            case 'time_picker':
                return 'H:i:s';
        }
        trigger_error('Unknown date time acf type: ' . $this->type);
    }

    public function getValueAttribute()
    {
        $date = Carbon::createFromFormat($this->internal_format, $this->internal_value, $this->getTimezoneString());

        // actually there is a requested format given in
        // $this->config['return_format'], but lets return a carbon instance,
        // this is probably more useful in most cases

        return $date;
    }
}
