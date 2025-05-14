
    document.addEventListener("DOMContentLoaded", function () {
        let packageSelect = document.getElementById("package");
        let priceInput = document.getElementById("price");
        let paymentInput = document.getElementById("payment");
        let remainingPaymentInput = document.getElementById("remaining-payment");
    
        // Get the prices from data attributes of the options
        let prices = {};
        Array.from(packageSelect.options).forEach(option => {
            if (option.value) {
                prices[option.value] = parseFloat(option.dataset.price);
            }
        });
    
        // Update package price when package is selected
        packageSelect.addEventListener("change", function () {
            let selectedPackage = packageSelect.value;
            let selectedOption = packageSelect.options[packageSelect.selectedIndex];
            
            if (selectedPackage && selectedOption.dataset.price) {
                const packagePrice = parseFloat(selectedOption.dataset.price);
                priceInput.value = packagePrice.toFixed(2);
                remainingPaymentInput.value = packagePrice.toFixed(2);
                paymentInput.value = ""; // Clear payment field when package changes
            } else {
                priceInput.value = "";
                remainingPaymentInput.value = "";
            }
        });
         // Update remaining payment when payment amount is entered
    paymentInput.addEventListener("input", function () {
        let price = parseFloat(priceInput.value) || 0;
        let payment = parseFloat(paymentInput.value) || 0;

        // If payment exceeds the price, reject it and set the value back to the price
        if (payment > price) {
            alert("Payment cannot exceed the package price.");
            paymentInput.value = price; // Set paym
            // ent back to the package price
            payment = price; // Set payment to package price for remaining calculation
        }

        let remaining = price - payment;

        // Update the remaining payment (ensure it doesn't show negative)
        remainingPaymentInput.value = remaining >= 0 ? remaining : 0;
    });
    
       
    });

   
