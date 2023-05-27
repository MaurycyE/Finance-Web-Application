
function findRecordInDatabase(id, name) {


    if (name == "expense") {

        document.getElementById('expenseRecordId').value = Number(document.getElementById(id + '_eid').innerHTML);
        document.getElementById('expenseRecord').value = name;

        document.getElementById('editRecordAmout').value = Number(document.getElementById(id + '_ea').innerHTML);

        let selectCategory = document.getElementById(document.getElementById(id + '_ec').innerHTML);
        let selectPaymentMethod = document.getElementById(document.getElementById(id + '_ep_m').innerHTML);

        selectCategory.selected = 'selected';
        selectPaymentMethod.selected = 'selected';

        document.getElementById('editRecordDate').value = document.getElementById(id + '_eda').innerHTML;
        document.getElementById('editRecordDescription').value = document.getElementById(id + '_ede').innerHTML;
    }

    else {

        document.getElementById('incomeRecordId').value = Number(document.getElementById(id + '_iid').innerHTML);
        document.getElementById('incomeRecord').value = name;

        document.getElementById('editIncomeRecordAmout').value = Number(document.getElementById(id + '_ia').innerHTML);
        let selectIncomeCategory = document.getElementById(document.getElementById(id + '_ic').innerHTML);
        selectIncomeCategory.selected = 'selected';


        document.getElementById('editIncomeRecordDate').value = document.getElementById(id + '_ida').innerHTML;
        document.getElementById('editIncomeRecordDescription').value = document.getElementById(id + '_ide').innerHTML;

    }
}

// function enableDeleteButton(id, name) {

//     if (document.getElementById(id).checked == true)
//         document.getElementById(name).removeAttribute("disabled");

//     else
//         document.getElementById(name).setAttribute("disabled", "disabled");

// }