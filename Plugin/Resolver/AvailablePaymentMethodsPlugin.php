<?php

declare(strict_types=1);

namespace Elgentos\CustomerSpecificCheckMo\Plugin\Resolver;

use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\OfflinePayments\Model\Checkmo;
use Magento\QuoteGraphQl\Model\Resolver\AvailablePaymentMethods;

class AvailablePaymentMethodsPlugin
{
    private CurrentCustomer $customer;

    public function __construct(
        CurrentCustomer $customer
    ) {
        $this->customer = $customer;
    }

    public function afterResolve(
        AvailablePaymentMethods $availablePaymentMethods,
        array $result
    ): array {
        if (!$this->customer->getCustomerId()) {
            return $this->removeSpecifiedPaymentMethod(
                $result,
                Checkmo::PAYMENT_METHOD_CHECKMO_CODE
            );
        }

        $customer  = $this->customer->getCustomer();
        $attribute = $customer->getCustomAttribute('allowed_pay_through_checkmo');

        $allowedOnCredit = $attribute !== null && (bool)$attribute->getValue();

        foreach ($result as $index => $paymentMethod) {
            if (!$allowedOnCredit && $paymentMethod['code'] === Checkmo::PAYMENT_METHOD_CHECKMO_CODE) {
                unset($result[$index]);
            }
        }

        return $result;
    }

    private function removeSpecifiedPaymentMethod(
        array $paymentMethods,
        string $codeToRemove
    ): array {
        foreach ($paymentMethods as $index => $paymentMethod) {
            if ($paymentMethod['code'] === $codeToRemove) {
                unset($paymentMethods[$index]);
            }
        }

        return $paymentMethods;
    }
}
