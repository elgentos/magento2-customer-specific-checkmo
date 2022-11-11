## Customer specific Checkmo
If you allow certain customers to pay with an offline method like `checkmo` in this instance. 
This module provides just that.

### Prerequisites
Enable `checkmo` payment in the configuration.

### What does it do exactly 
This creates a customer attribute called `allowed_pay_through_checkmo`, which becomes a boolean toggle on the customer in the admin view.

When enabled the `checkmo` payment method is filtered out from customers who are not allowed to checkout with `checkmo`.

When the checkmo payment isn't enabled this module doesn't do anything.

### Todo
- Add more methods to be filtered perhaps through multi select.
- Add configuration to allow certain methods to be filtered.