function sanitize(string) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#x27;',
        "/": '&#x2F;',
    };
    const reg = /[&<>"'/]/ig;
    return string.replace(reg, (match)=>(map[match]));
}

function validatePhone(phone) {
    // const regex = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
    const regex = /^[a-zA-Z0-9\-().\s]{10,15}$/;
    return regex.test(phone);
}

function validateContactForms(subUrl=''){
    var xhr = new XMLHttpRequest(),
    url = '/contact-validator.json',
    inquiry_firstName = sanitize(document.getElementById('inquiry_firstname').value),
    inquiry_lastName = sanitize(document.getElementById('inquiry_lastname').value),
    inquiry_phone = sanitize(document.getElementById('inquiry_phone').value),
    inquiry_email = sanitize(document.getElementById('inquiry_email').value),
    inquiry_comments = sanitize(document.getElementById('inquiry_comments').value),
    country = document.getElementById('inquiry_country').value,
    data = 'first_name='+inquiry_firstName;
    data += '&last_name='+inquiry_lastName;
    data += '&phone='+inquiry_phone;
    data += '&email='+inquiry_email;

    var errors = '';
    var nameRegx = '/^([a-zA-Z]{2,})$/';

    if (inquiry_firstName == '') {
        errors += '<li>First Name Is Required</li>';
    }
    if (inquiry_lastName == '') {
        errors += '<li>Last Name Is Required</li>';
    }
    if (inquiry_phone == '') {
        errors += '<li>Phone Number Is Required</li>';
    }
    if (inquiry_email == '') {
        errors += '<li>Email Is Required</li>';
    }
    if (inquiry_comments == '') {
        errors += '<li>Comments Are Required</li>';
    }
    
    if(errors == ''){
        if (inquiry_firstName.match(nameRegx)) {
            errors += '<li>First name can contain only alphabets, and should be at least two characters long</li>';
        }
    
        if (inquiry_lastName.match(nameRegx)) {
            errors += '<li>First name can contain only alphabets, and should be at least two characters long</li>';
        }
    
        if (country != 'USA') {
            if (!validatePhone(inquiry_phone)) {
                errors += '<li>Phone Number is invalid</li>';
            }
        
            if (!validateEmail(inquiry_email) || inquiry_email.match('\b.ru\b')) {
                errors += '<li>The email address is invalid</li>';
            }
    
            if (errors != '') {
                document.getElementById('errors').innerHTML = errors;
                document.getElementById('formError').classList.remove('hide');
                document.getElementById('banner').scrollIntoView();
            }else{
                document.getElementById("contact-form").action = subUrl+'/updated';
                document.getElementById("contact-form").submit();
            }
        }else{
            xhr.open("POST", url);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var json = JSON.parse(xhr.responseText);
                    // console.log(json);
                    var errors = '';
                    if (json.status == 'success') {
                        document.getElementById("contact-form").action = subUrl+'/updated';
                        document.getElementById("contact-form").submit();
                    }else{
                        if (json.phone_score < 25) {
                            errors += '<li>Phone Number is invalid</li>';
                        }
    
                        if (json.email_score < 49) {
                            errors += '<li>The email address is invalid</li>';
                        }
    
                        if (errors != '') {
                            document.getElementById('errors').innerHTML = errors;
                            document.getElementById('formError').classList.remove('hide');
                            document.getElementById('banner').scrollIntoView();
                        }
                    }
                }
            }
            xhr.send(data);
        }
    }else{
        document.getElementById('errors').innerHTML = errors;
        document.getElementById('formError').classList.remove('hide');
        document.getElementById('banner').scrollIntoView();
    }
}