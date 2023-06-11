function selectIncomeCategoryToRename(id) {

    let selectedCategory = document.getElementById(id).innerHTML;

    document.getElementById("oldNameIncomeCategory").value = selectedCategory;
    document.getElementById("newName").value = selectedCategory;
}

function selectIncomeCategoryToDelete(id) {

    let selectedCategory = document.getElementById(id).innerHTML;

    document.getElementById("selectCategoryToDelete").value = selectedCategory;
    document.getElementById("displayCategoryToDelete").value = selectedCategory;

}