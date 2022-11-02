const button = document.getElementById('button'),
    mainInput = document.getElementById('main-input'),
    main = document.getElementById('main'),
    mainContent = document.getElementById('main-errors');


button.addEventListener("click", () => {
    mainContent.replaceChildren();
    let names = document.getElementById('name').value;
    let surname = document.getElementById('surname').value;
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;
    let repassword = document.getElementById('repassword').value;
    console.log(email);
    $.ajax({
        url: 'main.php',
        method: 'POST',
        data: {names: names, surname: surname, email: email, password: password, repassword: repassword},
        success:function(data){
            console.log(data);
            if(data == 'true'){
                mainContent.style.display = 'none';
                mainInput.style.display = 'none';
                const message = document.createElement('p');
                message.style.fontSize = '25px';
                message.style.width = '270px';
                message.textContent = 'Вы зарегистрированы!';
                main.append(message);
            }
            else{
                mainContent.style.display = 'flex';
                const error = document.createElement('p');
                error.textContent = data;
                error.style.minWidth = '190px';
                error.display = 'inline';
                mainContent.prepend(error);
            }
        }
    });
});