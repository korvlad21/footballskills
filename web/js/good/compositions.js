let buttons = document.querySelectorAll("#addComposition");
let result = document.querySelector("#result");


function remove(e) {
    let key = e.parentElement.getAttribute('data');

    e.parentElement.remove();

}

const linkBuilder = (key, value, id)=>{

    let str  = `
        <div data="${key}">
                    <label>${key}</label>
                    </br>
                    <input type = "text" value="${value}" name="compositions[${id}]"  > <span style="cursor: pointer; margin-left: 5px; font-size: 1.2em" onclick="remove(this)">X</span>
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

        let id = e.target.parentElement.children[0].value;
        //console.log(id);
        const options = e.target.parentElement.children[0].options;


        for(let i of options){
            if(i.getAttribute('value')==key){
                key = i.innerHTML;

            }
            //console.log(i);
        }

        //console.log(key);
        let value = e.target.parentElement.children[1].value;



        linkBuilder(key, value, id);
    });
}