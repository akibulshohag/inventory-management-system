$(document).ready(function () {
    var DOMAIN = "http://localhost/inventory_project2/public_html";

    // manage catagory
    manageCategory();
    function manageCategory() {
        $.ajax({
            url: DOMAIN + "/includes/process.php",
            method: "POST",
            data: { manageCategory: 1 },
            success: function (data) {
                $("#get_category").html(data);
                

            }
        })
    }
    // $("body").delegate(".page_link","click",function(){
    //     var pn = $(this).attr("pn");
    //     manageCategory(pn);
    // })
// delete cat
    $("body").delegate(".del_cat","click",function() {
        var did  = $(this).attr("did");
        if(confirm("Confirm Delete?"))
        {
            $.ajax({
                url: DOMAIN + "/includes/process.php",
                method: "POST",
                data: {deleteCategory:1,id:did},
                success: function (data) {
                    
                    if (data == "Dependent")
                    {
                        alert("Cannot Delete a Parent Category");
                    }
                    else if (data == "Category_Deleted")
                    {
                        alert("Category Deleted");
                        manageCategory(1);
                    }
                    else if (data == "Deleted")
                    {
                        alert("Deleted");
                    }
                    else{
                        alert(data);
                    }
                  
                }
            })
        }
        
        
    })

    //Fetch category
    fetch_category();
    function fetch_category() {
        $.ajax({
            url: DOMAIN + "/includes/process.php",
            method: "POST",
            data: { getCategory:1},
            success: function (data) {
                var root = "<option value='0'> ROOT</option>";
                var chose = "<option value=''> Chose Category</option>";
                
                $("#parent_cat").html(root + data);
                $("#select_cat").html(chose+data);
               
            }
        })
    }

    // fetch brands
    fetch_brand();
    function fetch_brand() {
        $.ajax({
            url: DOMAIN + "/includes/process.php",
            method: "POST",
            data: { getbrand: 1 },
            success: function (data) {


                var chose = "<option value='0'>Choose Brand</option>";

                $("#select_brand").html(chose + data);
            }
        })
    }
    // update category
    $("body").delegate(".edit_cat","click", function () {
        var eid = $(this).attr("eid");
        $.ajax({
            url: DOMAIN+"/includes/process.php",
            method: "POST",
            dataType : "json",
            data: {updateCategory:1,id:eid},
            success : function (data) {
                console.log(data);
                $("#cid").val(data["cid"]);
                $("#update_catagory").val(data["catagory_name"]);
                $("#parent_cat").val(data["parent_cat"]);
            }
        })
    })
 

    $("#update_category_form").on("submit", function() {
    
        if ($("#update_catagory").val() == "") {
            $("#update_catagory").addClass("border-danger");
            $("#cat_error").html("<span class='text-danger'>Please Enter Category Name</span>");
        } else {
            $.ajax({
                url: DOMAIN + "/includes/process.php",
                method: "POST",
                data: $("#update_category_form").serialize(),
                success: function (data) {
                   window.location.href = "";
                    

                }
            })
        }
    })
    // manage brand
    manageBrand();
    function manageBrand() {
        $.ajax({
            url: DOMAIN + "/includes/process.php",
            method: "POST",
            data: { manageBrand: 1 },
            success: function (data) {
                $("#get_brand").html(data);


            }
        })
    }

    // delete brands
    $("body").delegate(".del_brand", "click", function () {
        var did = $(this).attr("did");
        
        if (confirm("Confirm Delete?")) {
            $.ajax({
                url: DOMAIN + "/includes/process.php",
                method: "POST",
                data: { deleteBrand: 1, id: did },
                success: function (data) {
                    if (data == "DELETED")
                    {
                        //alert("Brand Deleted Successfuly");
                        manageBrand(1);

                    }
                    else{
                        alert(data);
                    }

                }
            })
        }

    })
    // update brand
    $("body").delegate(".edit_brand", "click", function () {
        var eid = $(this).attr("eid");
        $.ajax({
            url: DOMAIN + "/includes/process.php",
            method: "POST",
            dataType: "json",
            data: { updateBrand: 1, id: eid },
            success: function (data) {
                console.log(data);
                $("#bid").val(data["bid"]);
                $("#update_brand").val(data["brand_name"]);
                
            }
        })
    })
    
    $("#update_brand_form").on("submit", function () {

        if ($("#update_brand").val() == "") {
            $("#update_brand").addClass("border-danger");
            $("#brand_error").html("<span class='text-danger'>Please Enter Brand Name</span>");
        } else {
            $.ajax({
                url: DOMAIN + "/includes/process.php",
                method: "POST",
                data: $("#update_brand_form").serialize(),
                success: function (data) {
                   
                        window.location.href =  "";
                    

                }
            })
        }
    })

    // manage Product
    manageProduct();
    function manageProduct() {
        $.ajax({
            url: DOMAIN + "/includes/process.php",
            method: "POST",
            data: { manageProduct: 1 },
            success: function (data) {
                $("#get_product").html(data);


            }
        })
    }
    
    // delete products
   
    $("body").delegate(".del_product", "click", function () {
        var did = $(this).attr("did");

        if (confirm("Confirm Delete?")) {
            $.ajax({
                url: DOMAIN + "/includes/process.php",
                method: "POST",
                data: { deleteProduct: 1, id: did },
                success: function (data) {
                    if (data == "DELETED") {
                        
                        manageProduct(1);

                    }
                    else {
                        alert(data);
                    }

                }
            })
        }

    })
    // update product
    $("body").delegate(".edit_product", "click", function () {
        var eid = $(this).attr("eid");
        $.ajax({
            url: DOMAIN + "/includes/process.php",
            method: "POST",
            dataType: "json",
            data: { updateProduct: 1, id: eid },
            success: function (data) {
                console.log(data);
                $("#pid").val(data["pid"]);
                $("#update_product").val(data["product_name"]);
                $("#select_cat").val(data["cid"]);
                $("#select_brand").val(data["bid"]);
                $("#product_price").val(data["product_price"]);
                $("#product_qty").val(data["product_stock"]);

            }
        })
    })

    // update product
    $("#update_product_form").on("submit", function () {
        
        $.ajax({
            url: DOMAIN + "/includes/process.php",
            method: "POST",
            data: $("#update_product_form").serialize(),
            success: function (data) {
               window.location.href = "";

            }
        })
    })

    // update profile
    $("#update_pro_form").on("submit", function () {

        if ($("#update_p_name").val() == "") {
            $("#update_p_name").addClass("border-danger");
            $("#pro_error").html("<span class='text-danger'>Please Enter Profile Name</span>");
        } else {
            $.ajax({
                url: DOMAIN + "/includes/process.php",
                method: "POST",
                data: $("#update_pro_form").serialize(),
                success: function (data) {
                    window.location.href = "";


                }
            })
        }
    })





})