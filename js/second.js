

// Уменьшает кол-во блюд
function decrementNumber(productCounter, productInput) {
    const value = Number(productCounter.textContent);
    const newValue = value - 1;

    if (value <= 1) {
        button.classList.remove("hidden");
        counter.classList.add("hidden");

        itemNode.textContent = 1;
        inputNode.value = 1;

        itemCounter.removeEventListener("click", handleCounterClick);
    } else {
        itemNode.textContent = newValue;
        inputNode.value = newValue;

        // Закидывает данные в Локал-Сторедж 
        setBasketItem(orderId, productDataId, newValue);
    }
}