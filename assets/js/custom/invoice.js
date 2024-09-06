$(document).ready(function () {
    "use strict";

    let invoice = $('#invoice').html();

    let clearInvoice = $('meta[name="clear-invoice-html"]').attr('content');
    if (clearInvoice) {
        $('#invoice').html('');
    }

    $(document).on('click', '.m-add', function () {
        $('#invoice').append(invoice);
    });

    $(document).on('click', '.m-remove', function () {
        $(this).closest('tr').remove();
        updateTotals();
    });

    function calculateTotal() {
        let total = 0;
        $('.price').each(function() {
            let row = $(this).closest('tr');
            let quantity = parseFloat(row.find('.quantity').val()) || 0;
            let price = parseFloat($(this).val()) || 0;
            total += quantity * price;
        });
        $('.total').val(total.toFixed(2));
    }

    function updateTotals() {
        calculateTotal();
        calculateTotalDiscount();
        calculateVat();
    }

    function calculateTotalDiscount() {
        let totalDiscount = 0;

        $('.discount_pct').each(function () {
            let row = $(this).closest('tr');
            let quantity = parseFloat(row.find('.quantity').val()) || 0;
            let price = parseFloat(row.find('.price').val()) || 0;
            let discountPercentage = parseFloat($(this).val()) || 0;

            if (discountPercentage > 0 && discountPercentage <= 100) {
                let subTotal = quantity * price;
                let discount = (discountPercentage / 100) * subTotal;
                totalDiscount += discount;
            }
        });

        $('.total_discount').val(totalDiscount.toFixed(2));
    }

    function calculateVat() {
        let total = parseFloat($('.total').val()) || 0;
        let totalDiscount = parseFloat($('.total_discount').val()) || 0;
        let vatPercentage = parseFloat($('.vat_percentage').val()) || 0;

        let totalAfterDiscount = total - totalDiscount;
        let vat = (vatPercentage / 100) * totalAfterDiscount;
        $('.vat').val(vat.toFixed(2));

        let grandTotal = totalAfterDiscount + vat;
        $('.grand_total').val(grandTotal.toFixed(2));

        calculatePaid();
    }

    function calculatePaid() {
        let grandTotal = parseFloat($('.grand_total').val()) || 0;
        let paid = parseFloat($('.paid').val()) || 0;

        let due = grandTotal - paid;
        $('.due').val(due.toFixed(2));
    }

    $(document).on('change keyup', '.quantity, .price, .discount_pct', function () {
        let row = $(this).closest('tr');
        let quantity = parseFloat(row.find('.quantity').val()) || 0;
        let price = parseFloat(row.find('.price').val()) || 0;
        let discountPct = parseFloat(row.find('.discount_pct').val()) || 0;

        if (discountPct < 0) {
            discountPct = 0;
            row.find('.discount_pct').val(discountPct.toFixed(2));
        } else if (discountPct > 100) {
            discountPct = 100;
            row.find('.discount_pct').val(discountPct.toFixed(2));
        }

        let subTotal = quantity * price;
        if (discountPct > 0) {
            subTotal -= (discountPct / 100) * subTotal;
        }

        row.find('.sub_total').val(subTotal.toFixed(2));
        updateTotals();
    });

    $(document).on('change keyup', '.vat_percentage, .paid', function () {
        updateTotals();
    });

    $(document).on('change', '.procedure-select', function () {
        let row = $(this).closest('tr');
        let quantity = row.find('.quantity');
        if (quantity.val() == 0) {
            quantity.val(1);
        }
    });
});
