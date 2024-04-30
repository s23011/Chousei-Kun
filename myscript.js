check_event_info();

function check_event_info(){
    $.ajax({
        url: 'controller/check_event.php',
        method: 'POST',
        dataType: "json",
        success: function(event_info) {
            testMsg(event_info.event_name);
            window.console.log(event_info);
        },
        error: function(error) {
        }
    });
}

function set_event_info(){
    // elm_g_list = document.querySelector('#g_list');
    // let elm_list = elm_g_list;
    // let elm_list_item = elm_list.children[0];
    // //delet test html
    // let child_count = elm_list.children.length;
    // for(let n=1; n < child_count;n++){
    //     elm_list.children[1].remove();
    // }
    
    
    // for(let n=0; n < list_capacity; n++){
    //     let elm_new = elm_list_item.cloneNode(true);
    //     elm_list.append(elm_new);
    // }

    // //delet test html
    // elm_list_item.remove();

    // setPageList();
}



function testMsg(msg)
{
    window.alert(msg);
}
function test()
{
    testMsg('Go');
}

