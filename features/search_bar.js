function fill(symbol)
{
    let search_bar = document.getElementById('symbol');
    search_bar.value = symbol;
}


let input = document.getElementById('symbol');
    input.addEventListener('input', async function () {
        let response = await fetch('./company_data.php?q=' + input.value);
        let result = await response.json();
        let html = '';
        for (let id in result) {
            let name = result[id].Name;
            let symbol = result[id].Symbol;
            let industry = result[id].Industry;
            html += '<li>' + '<button onclick="fill('+ '`' + symbol + '`' + ')">' + '<p>' + name + '</p>' + '<p>' + symbol + '</p>' + '<p>' + industry + '</p>' + '</button>' + '</li>';
        }
        document.getElementById('suggestions').innerHTML = html;
    });