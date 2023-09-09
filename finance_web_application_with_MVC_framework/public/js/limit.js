
const categoryField = document.getElementById("add-expense-category");
const limitField = document.getElementById("limitField");
const dateField = document.getElementById("add-expense-date");
const moneyLeftField = document.getElementById("moneyLeftField");
const sumField = document.getElementById("sumExpenseField");
const expenseAmoutField = document.getElementById("add-expense-amout");
const submitButton = document.getElementById("submitButton");
const resetButton = document.getElementById("resetButton");

let limit;
let sum;
let selectedDate;
let currentSelectedCategory;
let amout;


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

    sum = null;
    selectedDate = null;
    currentSelectedCategory = null;
    amout = null;

    showLimit(defaultCategory);
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

        limit = data;

        if (data === null) {

            data = "Nie ustawiono limitu";
        }

        limitField.textContent = data;

        if (limit === null) {

            moneyLeftField.textContent = "Nie ustawiono limitu";
            removeTextColor();
        }
        else {

            if (amout !== null) {

                moneyLeftField.textContent = Number(limit) - Number(sum) - Number(amout);
                changeTextColor(limit, sum, amout);
            }
            else if (limit !== null && sum !== null) {

                moneyLeftField.textContent = Number(limit) - Number(sum);
                changeTextColor(limit, sum, 0);
            }
            else {

                //moneyLeftField.textContent = limit;
                moneyLeftField.textContent = "wybierz datę";
                //changeTextColor(limit, 0, 0);
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

        sum = data;

        sumField.textContent = data;

        if (limit === null) {

            moneyLeftField.textContent = "Nie ustawiono limitu";
            removeTextColor();
        }
        else {

            if (amout !== null) {

                moneyLeftField.textContent = Number(limit) - Number(sum) - Number(amout);
                changeTextColor(limit, sum, amout);
            }
            else if (limit !== null && sum !== null) {

                moneyLeftField.textContent = Number(limit) - Number(sum);
                changeTextColor(limit, sum, 0);
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

    currentSelectedCategory = event.target.value;

    await showLimit(category);
    await showSum(selectedDate, category);
})

dateField.addEventListener("change", async (event) => {

    let date = event.target.value;
    selectedDate = event.target.value;

    if (currentSelectedCategory == null) {

        currentSelectedCategory = defaultCategory;
    }

    await showSum(date, currentSelectedCategory);

})

expenseAmoutField.addEventListener("input", async (event) => {

    if (limit !== null) {

        amout = event.target.value;

        moneyLeftField.textContent = Number(limit) - Number(sum) - Number(amout);

        changeTextColor(limit, sum, amout);
    }

})

submitButton.addEventListener("click", resetFieldsState);
resetButton.addEventListener("click", resetFieldsState);