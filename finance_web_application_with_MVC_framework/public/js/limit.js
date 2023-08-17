
const categoryField = document.getElementById("add-expense-category");
const limitField = document.getElementById("limitField");


const getSelectedCategoryLimit = async (category) => {

    try {

        const res = await fetch(`../api/limit/${category}`);
        const data = await res.json();
        return data;
    } catch (e) {

        console.log('ERROR', e);
    }
}

const showLimit = async (category) => {

    // let category = document.getElementById(id).innerHTML;
    // console.log(id);
    // let category = "jedzenie";

    try {

        const res = await fetch(`https://budget.slawomir-gorczynski.profesjonalnyprogramista.pl/api/limit/${category}`);
        const data = await res.json();

        limitField.textContent = data;
        //console.log(data);
    } catch (e) {

        console.log('ERROR: ', e);
    }

}

categoryField.addEventListener("change", async (event) => {

    let category = event.target.value;
    await showLimit(category);
})
