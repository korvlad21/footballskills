let buttons = document.querySelectorAll("#addSocial");
let result = document.querySelector("#result");


function remove(e) {
    let key = e.parentElement.getAttribute('data');

    e.parentElement.remove();

    let input = `<input type="hidden" name="socials[${key}]" value="" >`;

    result.innerHTML+=input;
}

const linkBuilder = (key, value)=>{

    let str  = `
        <div data="${key}">
                    <label>${key}</label>
                    </br>
                    <input type = "text" value="${value}" name="socials[${key}]"  > <span style="cursor: pointer; margin-left: 5px; font-size: 1.2em" onclick="remove(this)">X</span>
                    </br>
        </div>
`;

    result.innerHTML+=str;

};


for(let button of buttons)
{
    button.addEventListener('click',(e)=>{

        e.preventDefault();


        let key = e.target.parentElement.children[0].value;
        let value = e.target.parentElement.children[1].value;



        linkBuilder(key, value);
    });
}