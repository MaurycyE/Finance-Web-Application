
const categoryField = document.getElementById("add-expense-category");
const limitField = document.getElementById("limitField");
const dateField = document.getElementById("add-expense-date");
const moneyLeftField = document.getElementById("moneyLeftField");
const sumField = document.getElementById("sumExpenseField");
const expenseAmoutField = document.getElementById("add-expense-amout");
const submitButton = document.getElementById("submitButton");
const resetButton = document.getElementById("resetButton");

const limitElements = {

    defaultCategory: document.getElementById("0_ae").value,
    limit: null,
    sum: null,
    selectedDate: null,
    currentSelectedCategory: null,
    amout: null,
};


function removeTextColor() {

    moneyLeftField.classList.remove("text-danger");
    moneyLeftField.classList.remove("text-success");
}

function changeTextColor(limit, sum, amout) {


    if (Number(limit) - Number(sum) - Number(amout) < 0) {

        moneyLeftField.classList.remove("text-success");
        moneyLeftField.classList.add("text-danger");

    }
    else {

        moneyLeftField.classList.remove("text-danger");
        moneyLeftField.classList.add("text-success");
    }

}

function resetFieldsState() {

    limitElements.sum = null;
    limitElements.selectedDate = null;
    limitElements.currentSelectedCategory = null;
    limitElements.amout = null;

    showLimit(limitElements.defaultCategory);
    sumField.textContent = "wybierz datę";
    removeTextColor();

}

const getSelectedCategoryLimit = async (category) => {

    try {

        const res = await fetch(`../api/limit/${category}`);
        const data = await res.json();
        return data;
    } catch (e) {

        console.log('ERROR', e);
    }
}

const getSelectedCategoryExpensesSumInSelectedMonth = async (date, category) => {

    try {

        const res = await fetch(`../api/sum/${date}/${category}`);
        const data = await res.json();
        return data;

    } catch (e) {

        console.log('ERROR', e);
    }
}

const showLimit = async (category) => {

    try {

        const res = await fetch(`https://budget.slawomir-gorczynski.profesjonalnyprogramista.pl/api/limit/${category}`);
        let data = await res.json();

        limitElements.limit = data;

        if (data === null) {

            data = "Nie ustawiono limitu";
        }

        limitField.textContent = data;

        if (limitElements.limit === null) {

            moneyLeftField.textContent = "Nie ustawiono limitu";
            removeTextColor();
        }
        else {

            if (limitElements.amout !== null && limitElements.sum !== null) {

                moneyLeftField.textContent = Number(limitElements.limit) - Number(limitElements.sum) - Number(limitElements.amout);
                changeTextColor(limitElements.limit, limitElements.sum, limitElements.amout);
            }
            else if (limitElements.limit !== null && limitElements.sum !== null) {

                moneyLeftField.textContent = Number(limitElements.limit) - Number(limitElements.sum);
                changeTextColor(limitElements.limit, limitElements.sum, 0);
            }
            else {

                moneyLeftField.textContent = "wybierz datę";
            }

        }

    } catch (e) {

        console.log('ERROR: ', e);
    }

}

const showSum = async (date, category) => {

    try {

        const res = await fetch(`https://budget.slawomir-gorczynski.profesjonalnyprogramista.pl/api/sum/${date}/${category}`);
        let data = await res.json();

        limitElements.sum = data;

        sumField.textContent = data;

        if (limitElements.limit === null) {

            moneyLeftField.textContent = "Nie ustawiono limitu";
            removeTextColor();
        }
        else {

            if (limitElements.amout !== null) {

                moneyLeftField.textContent = Number(limitElements.limit) - Number(limitElements.sum) - Number(limitElements.amout);
                changeTextColor(limitElements.limit, limitElements.sum, limitElements.amout);
            }
            else if (limitElements.limit !== null && limitElements.sum !== null) {

                moneyLeftField.textContent = Number(limitElements.limit) - Number(limitElements.sum);
                changeTextColor(limitElements.limit, limitElements.sum, 0);
            }
            else
                moneyLeftField.textContent = "wybierz datę";

        }
    }
    catch (e) {

        console.log('ERROR: ', e);
    }

}

categoryField.addEventListener("change", async (event) => {

    let category = event.target.value;

    limitElements.currentSelectedCategory = event.target.value;

    await showLimit(category);
    await showSum(limitElements.selectedDate, category);
})

dateField.addEventListener("change", async (event) => {

    let date = event.target.value;
    limitElements.selectedDate = event.target.value;

    if (limitElements.currentSelectedCategory == null) {

        limitElements.currentSelectedCategory = limitElements.defaultCategory;
    }

    await showSum(date, limitElements.currentSelectedCategory);

})

expenseAmoutField.addEventListener("input", async (event) => {

    if (limitElements.limit !== null && limitElements.sum !== null) {

        limitElements.amout = event.target.value;

        moneyLeftField.textContent = Number(limitElements.limit) - Number(limitElements.sum) - Number(limitElements.amout);

        changeTextColor(limitElements.limit, limitElements.sum, limitElements.amout);
    }

})

submitButton.addEventListener("click", resetFieldsState);
resetButton.addEventListener("click", resetFieldsState);