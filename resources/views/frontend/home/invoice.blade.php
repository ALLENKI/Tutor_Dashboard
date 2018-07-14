
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Aham Learning Hub Receipt</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
      .h-pull-right{float:right}.financial_document{background:whitesmoke;font:14px/21px sans-serif}@media print{.financial_document{background:white}}.invoice{border-radius:4px;margin:0 auto;width:900px;position:relative;background:white;font-size:16px;line-height:1.5;color:#999999}.invoice a{color:#0084B4}.invoice strong{color:#454545}.invoice__column{float:left;padding-right:15px;box-sizing:border-box}.invoice__column.-span-10{width:10%}.invoice__column.-span-20{width:20%}.invoice__column.-span-25{width:25%}.invoice__column.-span-30{width:30%}.invoice__column.-span-35{width:35%}.invoice__column.-span-40{width:40%}.invoice__column.-span-50{width:50%}.invoice__column.-span-60{width:60%}.invoice__column.-span-75{width:75%}.invoice__column.-last{float:right;padding:0}.invoice__column.-left-padded{padding-left:25px}.invoice__header{background:#454545;border-radius:4px 4px 0 0;color:white;display:block;height:60px;line-height:60px;width:100%}@media print{.invoice__header{background:whitesmoke;color:#454545}}.invoice__supplier{margin-top:20px}.invoice__envato-logo{float:left;width:150px;padding-left:20px}.invoice__envato-logo img{height:40px}.envato-logo--print{display:none}@media print{.envato-logo--print{display:inline}}.envato-logo--screen{display:inline}@media print{.envato-logo--screen{display:none}}.invoice__document-title{float:right;padding-right:20px;font-size:16px;font-weight:700}.invoice__details{padding:20px 40px}.invoice__details .right span{display:inline-block}.invoice__details-label{vertical-align:top;padding-right:30px;font-weight:700;color:#454545}.invoice__details-content{display:inline-block}.invoice__details:after{content:"";display:table;clear:both}.invoice__item-container{background:whitesmoke;padding:20px;margin-bottom:20px}.invoice__lines{padding:40px}.invoice__lines h3{font-size:16px;font-weight:700;padding-bottom:20px;color:#454545}.invoice__lines table{border-collapse:separate;font-size:14px;width:100%;page-break-inside:auto}.invoice__lines table .invoice__th--price,.invoice__lines table .invoice__th--rate,.invoice__lines table .invoice__td--price,.invoice__lines table .invoice__td--rate{text-align:right;padding:0}.invoice__lines table th{line-height:2;text-align:left;font-weight:bold;color:#454545;padding-right:15px}.invoice__lines table tr{page-break-inside:avoid;page-break-after:auto}.invoice__lines table td{text-align:left;vertical-align:top;padding-right:15px;padding-top:2px}.invoice__lines table td.invoice__td--id{width:10%}.invoice__lines table td.invoice__td--entity{width:20%}.invoice__lines table td.invoice__td--description{width:60%}.invoice__lines table td.invoice__td--quantity{width:10%}.invoice__lines table td.invoice__td--price{text-align:right;width:20%}.invoice__lines table td.invoice__td--rate{text-align:right;width:10%}.invoice__lines table td.invoice__td--total{text-align:right}.invoice__lines table td.invoice__th--rate{text-align:right}.invoice__footer{padding:0 40px}.invoice__notice{font-size:14px}.invoice__notice.-margin-top{margin-top:10px}.invoice__notice.-margin-bottom{margin-bottom:10px}.invoice__footnotes{padding:40px;padding-top:0px;text-align:center}.invoice__footnotes.-left-align{text-align:left}.invoice__total{color:#454545;font-weight:700;text-align:right;margin-top:10px}.invoice__total.-outside-container{padding-right:20px;margin-top:0}.invoice__total.-amount{font-size:24px}.invoice__total-amount{color:#454545;font-size:32px;font-weight:700;line-height:1;padding-right:20px;text-align:right}.invoice__payment-method{text-align:right}.invoice__footer:after{content:"";display:table;clear:both}
    </style>
  </head>
  <body class="financial_document">
    <div class="invoice">
  <div class="invoice__header" style="background-color: white;">
  <div class="invoice__envato-logo" style="padding-top:40px;">
    <img class="envato-logo--screen" src="https://res.cloudinary.com/ahamlearning/image/upload/c_fit,q_auto:eco,f_auto/v1466848309/logo_huge_lfpjpa.png" alt="Envato market" />
    <img class="envato-logo--print" src="https://res.cloudinary.com/ahamlearning/image/upload/c_fit,q_auto:eco,f_auto/v1466848309/logo_huge_lfpjpa.png" alt="Envato market black" />
  </div>

  <div class="invoice__document-title">
    Receipt
  </div>
</div>

  <div class="invoice__details">
  <div class="invoice__column -last">
    <div class="invoice__date">
      <span class="invoice__details-label">Date:</span>
      <span class="invoice__details-content">{{ $creditModel->created_at->format('d M Y') }}</span>
    </div>
    <div class="invoice__number">
      <span class="invoice__details-label">Invoice No:</span>
      <span class="invoice__details-content">{{ $creditModel->invoice_no }}</span>
    </div>
  </div>
</div>

<div class="invoice__details">
  <div class="invoice__column -span-50">
    <div class="invoice__buyer">
      <strong>To:</strong><br/>
      {{ $creditModel->student->user->name }}
      <br>
      {{ $creditModel->student->user->email }}
    </div>
  </div>

  <div class="invoice__column -span-50 -last">
    <div class="invoice__seller">
      <strong>From:</strong><br/>
      Aham Learning Hub Pvt. Ltd.
      <br>
      Synergy Building <br> Khajaguda,Hyderabad
    </div>
  </div>
</div>


  <div class="invoice__lines">
    <div class="invoice__item-container">
      <table>
        <thead>
          <tr>
            <th>Description</th>
            <th class="invoice__th--price">Amount</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td class="invoice__td--description">
              Payment to Buy Aham Credits
            </td>
            <td class="invoice__td--price">Rs.{{ inrFormat($creditModel->amount_paid) }}/-</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="invoice__footer">
  <div class="invoice__column -span-40 -last">
    <div class="invoice__total -outside-container">
      Total
    </div>
    <div class="invoice__total-amount">
      Rs.{{ inrFormat($creditModel->amount_paid) }}/-
    </div>
  </div>
</div>

<div class="invoice__footnotes">
  <div class="invoice__notice">
    @if(strtotime($creditModel->created_at->format('Y-m-d')) < strtotime('2017-06-30'))
    The rate is inclusive of service tax of 15%.
    @else  
    The rate is inclusive of GST of 18%.  
    @endif
  </div>
  <div class="invoice__notice">
   
  </div>

  
</div>

</div>


  </body>
</html>
