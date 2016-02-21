var $invoice_currency = $('input[name=invoice_currency]').val();
var environ = window.location.host;

if(environ.indexOf('localhost') > -1) {
  var baseurl = window.location.protocol + "//" + window.location.host + "/" + "rubyinvoice/";
} else {
  var baseurl = window.location.protocol + "//" + window.location.host + "/";
}

var app = angular.module('invoiceEditApp', [])
  .controller('InvoiceEditController', ['$scope', '$http', function($scope, $http) {

    $scope.loadData = function () {
     $http.get(baseurl+'index.php/invoices/get_favorite_invoice_items').success(function(data) {
       $scope.favorites = data;
     });
    };
    //initial load
    $scope.loadData();

    // process the form
    $scope.removeFav = function(id) {

      $http({
      method  : 'POST',
      url     : baseurl+'index.php/invoices/remove_favorite_invoice_item/'+id,
      data    : "message=" + id,
      headers: {'Content-Type': 'application/x-www-form-urlencoded'}

      }).success(function(data) {
        console.log(data);

        if (!data.success) {
        	// if not successful, bind errors to error variables
          $scope.message = data.errors;
          console.log("no success");

        } else {
        	// if successful, bind success message to message
          $scope.message = data.message;
          console.log("success");
          $scope.loadData();
        }
      });
    };

    // Unit-Type drop downs
    $scope.unit = {
    	hours: "hours",
    	days: "days",
    	services: "services",
    	products: "products"
    };

    // Currency drop down
    $scope.currencies = [
      {code: 'en_AU', symbol:'AUD $', label:'AUD (Australia)'},
      {code: 'pt_BR', symbol:'R$', label:'BRL (Brazil)'},
      {code: 'en_CA', symbol:'CAD $', label:'CAD (Canadian)'},
      {code: 'cs_CZ', symbol:'Kč', label:'CZK (Czech Republic)'},
      {code: 'da_DK', symbol:'kr', label:'DKK (Denmark)'},
      {code: 'nl_NL', symbol:'€', label:'EUR (Euro Member Countries)'},
      {code: 'de_DE', symbol:'€', label:'German (Germany)'},
      {code: 'el_GR', symbol:'€', label:'Greek (Greece)'},
      {code: 'hu_HU', symbol:'Ft', label:'HUF (Hungary)'},
      {code: 'he_IL', symbol:'₪', label:'ILS (Israel)'},
      {code: 'it_IT', symbol:'€', label:'Italian (Italy)'},
      {code: 'ja_JP', symbol:'¥', label:'JPY (Japan)'},
      {code: 'ko_KR', symbol:'₩', label:'Korean (South Korea)'},
      {code: 'en_NZ', symbol:'NZD $', label:'NZD (New Zealand)'},
      {code: 'pl_PL', symbol:'zł', label:'PLN (Poland)'},
      {code: 'ru_RU', symbol:'руб', label:'Russian (Russia)'},
      {code: 'de_CH', symbol:'CHF', label:'SHF (Switzerland)'},
      {code: 'en_GB', symbol:'£', label:'GBP (United Kingdom)'},
      {code: 'en_US', symbol:'$', label:'USD (United States)'}
    ];

    $scope.onCurrenciesOptionChange = function() {
			$scope.selectedVal = $scope.selectedCurrency.symbol;
		}

    // HACKERY NEEDED TO GET THE INITIAL CURRENCY CODE FROM DB
    var hasTag = function(code) {
	    var i = null;
	    for (i = 0; $scope.currencies.length > i; i += 1) {
	      if ($scope.currencies[i].code === $invoice_currency) {
	        return i;
	      }
	    }
      return false;
    };

    $scope.selectedCurrency = $scope.currencies[hasTag("search")]; // Matches the currency code from db to the index of the dropdown

    $scope.items = []; // Need an empty array to store new invoice item rows from template

    $scope.addInvoiceRow = function() {
    	$scope.items.push({});
    };

    $scope.addFavoriteRow = function(favorite) {
      $scope.items.push({
        description: favorite.description,
        qty: favorite.quantity,
        unit: favorite.unit,
        unit_cost: favorite.unit_cost,
        id: favorite.id,
        length: $scope.items.length
      });
      leng = $scope.items.length - 1;

      function f() {
        $("#qty-"+leng+" input.qty").focus();
        $("#qty-"+leng+" input.qty").focusout();
      }
      setTimeout(f, 100); // HACKISH WAY TO TARGET NEWER DOM ELEMENTS ADDED BY ANGULAR
    }

    $scope.getIncludeFile = function() {
	    return baseurl+'assets/html/invoice-row.html';
    }

    $scope.getFavModal = function() {
      return baseurl+'assets/html/favorites-modal.html';
    }

    /*
    $scope.filterItem = {
      theID: $scope.settings
    }
    */

    //Custom filter - filter based on the company selected
    /*
    $scope.customFilter = function (settings) {
      if (settings.company === $scope.filterItem.theID.company) {
        return true;
      } else {
        return false;
      }
    };
    */

}]);
