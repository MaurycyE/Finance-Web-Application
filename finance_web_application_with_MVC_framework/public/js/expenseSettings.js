function showExpenseCategoryNameToSetLimit(id) {

    let selectedCategory = document.getElementById(id + "_ec").innerHTML;

    document.getElementById("showCategoryNameToSetLimit").value = selectedCategory;
    // document.getElementById("oldCategoryName").value = selectedCategory;
    document.getElementById("expenseCategoryToSetLimit").value = "expenseCategory";
}

function showPaymentMethodToRename(id) {

    let selectedCategory = document.getElementById(id + "_pm").innerHTML;

    document.getElementById("selectedCategoryName").value = selectedCategory;
    document.getElementById("oldCategoryName").value = selectedCategory;
    document.getElementById("paymentMethodOrExpenseCategory").value = "paymentMethod";
}

function showExpenseCategoryToRename(id) {

    let selectedCategory = document.getElementById(id + "_ec").innerHTML;

    document.getElementById("selectedCategoryName").value = selectedCategory;
    document.getElementById("oldCategoryName").value = selectedCategory;
    document.getElementById("paymentMethodOrExpenseCategory").value = "expenseCategory";
}

function showPaymentMethodToDelete(id) {

    let selectedCategory = document.getElementById(id + "_pm").innerHTML;

    document.getElementById("showCategoryNameToDelete").value = selectedCategory;
    document.getElementById("categoryNameToDelete").value = selectedCategory;
    document.getElementById("specifyCategoryType").value = "paymentMethod";
}

function showExpenseCategoryToDelete(id) {

    let selectedCategory = document.getElementById(id + "_ec").innerHTML;

    document.getElementById("showCategoryNameToDelete").value = selectedCategory;
    document.getElementById("categoryNameToDelete").value = selectedCategory;
    document.getElementById("specifyCategoryType").value = "expenseCategory";
}