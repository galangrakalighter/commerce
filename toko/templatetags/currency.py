from decimal import Decimal, InvalidOperation

from django import template


register = template.Library()


@register.filter
def rupiah(value):
    """Format a numeric value using Indonesian Rupiah separators."""
    try:
        amount = Decimal(str(value))
    except (InvalidOperation, TypeError, ValueError):
        return "Rp 0"

    sign = "-" if amount < 0 else ""
    amount = abs(amount)
    whole, _, fraction = format(amount, "f").partition(".")
    grouped = f"{int(whole):,}".replace(",", ".")
    fraction = fraction.rstrip("0")
    decimal_part = f",{fraction}" if fraction else ""
    return f"{sign}Rp {grouped}{decimal_part}"
