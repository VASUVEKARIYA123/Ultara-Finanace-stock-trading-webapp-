let buy_buttons  = document.getElementsByName('buy');

for(let i=0;i<buy_buttons.length;i++)
{
    buy_buttons[i].addEventListener('click',function(ev){
        let form = ev.target.parentElement;
        ev.target.previousElementSibling.firstElementChild.removeAttribute("max");
        form.action = "buy.php"
    })
}

let sell_buttons  = document.getElementsByName('sell');

for(let i=0;i<sell_buttons.length;i++)
{
    sell_buttons[i].addEventListener('click',function(ev){
        let form = ev.target.parentElement;
        form.action = "sell.php"
    })
}