<?php

namespace App\Http\Requests\Validations;

use Auth;
use App\Address;
use App\Customer;
use App\Services\NewCustomer;
use App\Http\Requests\Request;
use App\Common\CanCreateStripeCustomer;

class CheckoutCartRequest extends Request
{
    use CanCreateStripeCustomer;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route('cart')) {
            return crosscheckCartOwnership($this, $this->route('cart'));
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->has('email')
            && $this->has('create-account')
            && $this->has('password')
            && ! Customer::where('email', $this->get('email'))->exists()
        )
        {
            $customer = (new NewCustomer)->save($this);
            $this->merge(['customer_id' => $customer]); // Set customer
        }

       // Create Stripe Customer for future use
        if (
            Auth::guard('customer')->check() &&
            $this->has('remember_the_card') &&
            $this->input('payment_method') == 'stripe'
        ) {
            $this->merge(['payee' => $this->createStripeCustomer()]); // Set Payee
        }

        // Get payment method id
        if ($this->payment_method) {
            $code = $this->payment_method == 'saved_card' ? 'stripe' : $this->payment_method;

            $this->merge(['payment_method_id' => get_id_of_model('payment_methods', 'code', $code)]); // Set payment method id
        }

        // Get shipping address
        if(is_numeric($this->ship_to)) {
            $address = Address::find($this->ship_to)->toHtml('<br/>', False);
        }
        else {
            $address = get_address_str_from_request_data($this);
        }

        $this->merge(['shipping_address' => $address]);  // Set Address

        $rules = [];
        if (! Auth::guard('customer')->check()) {
            $unique_ck = $this->has('create-account') ? '|unique:customers' : '';

            $rules['email'] =  'required|email|max:255' . $unique_ck;
            $rules['password'] =  'required_with:create-account|nullable|confirmed|min:6';
        }

        if('saved_card' != $this->payment_method){
            $rules['payment_method'] = ['required', 'exists:payment_methods,code,enabled,1'];
        }

        return $rules;
    }
}
