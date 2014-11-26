// ВСЕ ЭТО СПЛОШНЕЙШИЙ ГОВНОКОД!!!!
// ОПАСНО ДЛЯ ЖИЗНИ. НИКОГДА И НИКОМУ НЕ ПОКАЗЫВАЙТЕ ЕГО!!!!
// 
// Получаем все элементы с тэгом input и button
var allCheckboxs = document.getElementsByTagName("input");

// Функция считает количество checkbox'ов и если их меньше 2-х, т.е. 1,
// выключает его
window.onload = function iflessthan2() {
    var checkbox_count=0;
    
    for(var i=0; i<allCheckboxs.length; i++) {
        if(allCheckboxs[i].type && allCheckboxs[i].type === "checkbox") {
            checkbox_count++;
        }
    }
    
    if(checkbox_count < 2) {
        allCheckboxs[0].disabled = true;
    }
};

window.onclick = function() {
    var j=0;

    for(var i=0; i<allCheckboxs.length; i++) {
        if(allCheckboxs[i].type && allCheckboxs[i].type === "checkbox") {
            if(allCheckboxs[i].checked) {
                j++;
            }
        }
    }
    if(j === 2) {
        for(var i=0; i<allCheckboxs.length; i++) {
            if(allCheckboxs[i].type && allCheckboxs[i].type === "checkbox") {
                if(allCheckboxs[i].checked) {
                } else {
                    allCheckboxs[i].disabled = true;
                }
            }
            if(allCheckboxs[i].type && allCheckboxs[i].type === "submit") {
                if(allCheckboxs[i].disabled) {
                    allCheckboxs[i].disabled = false;
                }
            }
        }
    } else {
        for(var i=0; i<allCheckboxs.length; i++) {
            if(allCheckboxs[i].type && allCheckboxs[i].type === "checkbox") {
                if(allCheckboxs[i].disabled) {
                    allCheckboxs[i].disabled = false;
                }
            }
            if(allCheckboxs[i].type && allCheckboxs[i].type === "submit") {
                if(allCheckboxs[i].disabled) {
                } else {
                    allCheckboxs[i].disabled = true;
                }
            }                        
        }
    }

    if(j > 2) {
        alert("Для сравнения нельзя выбирать более двух версий конфига!");
    }
    j=0;
        
    var checkbox_count=0;
    
    for(var i=0; i<allCheckboxs.length; i++) {
        if(allCheckboxs[i].type && allCheckboxs[i].type === "checkbox") {
            checkbox_count++;
        }
    }
    
    if(checkbox_count < 2) {
        allCheckboxs[0].disabled = true;
    }
    
};