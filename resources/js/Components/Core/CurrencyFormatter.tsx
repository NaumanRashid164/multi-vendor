import React from 'react'

function CurrencyFormatter({ amount, currency = "USD", locale }: {
    amount: Number,
    currency?: string,
    locale?: string
}) {
    return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency
    }).format(amount)
}

export default CurrencyFormatter