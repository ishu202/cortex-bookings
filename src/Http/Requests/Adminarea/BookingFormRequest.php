<?php

declare(strict_types=1);

namespace Cortex\Bookings\Http\Requests\Adminarea;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class BookingFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $data = $this->all();

        // Calculate price
        $endsAt = $this->get('ends_at') ? new Carbon($this->get('ends_at')) : null;
        $startsAt = $this->get('starts_at') ? new Carbon($this->get('starts_at')) : null;
        $resource = app('cortex.bookings.resource')->find($this->get('resource_id'));
        list($price, $priceEquation, $currency) = app('rinvex.bookings.booking')->calculatePrice($resource, $startsAt, $endsAt);

        // Fill missing fields
        $data['ends_at'] = $endsAt;
        $data['starts_at'] = $startsAt;
        $data['customer_type'] = 'user';
        $data['resource_type'] = 'resource';
        $data['price_equation'] = $priceEquation;
        $data['currency'] = $currency;
        $data['price'] = $price;

        $this->replace($data);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $booking = $this->route('booking') ?? app('rinvex.bookings.booking');
        $booking->updateRulesUniques();

        return $booking->getRules();
    }
}
