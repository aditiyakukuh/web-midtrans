@extends('layouts.app')
@push('styles')
    <style>
        body{
            margin-top:20px;
            background:#eee;
        }
        .ui-w-40 {
            width: 40px !important;
            height: auto;
        }

        .card{
            box-shadow: 0 1px 15px 1px rgba(52,40,104,.08);    
        }

        .ui-product-color {
            display: inline-block;
            overflow: hidden;
            margin: .144em;
            width: .875rem;
            height: .875rem;
            border-radius: 10rem;
            -webkit-box-shadow: 0 0 0 1px rgba(0,0,0,0.15) inset;
            box-shadow: 0 0 0 1px rgba(0,0,0,0.15) inset;
            vertical-align: middle;
        }
    </style>
@endpush
@section('content')
<div class="container px-3 my-5 clearfix">
    <!-- Shopping cart table -->
    <div class="card">
        <div class="card-header">
            <h2>Shopping Cart</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered m-0">
                <thead>
                  <tr>
                    <!-- Set columns width -->
                    <th class="text-center py-3 px-4" style="min-width: 400px;">Product Name &amp; Details</th>
                    <th class="text-right py-3 px-4" style="width: 100px;">Price</th>
                    <th class="text-center py-3 px-4" style="width: 120px;">Quantity</th>
                    <th class="text-right py-3 px-4" style="width: 100px;">Total</th>
                    <th class="text-center align-middle py-3 px-0" style="width: 40px;"><a href="#" class="shop-tooltip float-none text-light" title="" data-original-title="Clear cart"><i class="ino ion-md-trash"></i></a></th>
                  </tr>
                </thead>
                <tbody>
                    
                @foreach ($cart->item as $key => $product)
                <tr>
                    <td class="p-4">
                      <div class="media align-items-center">
                        <img src="https://source.unsplash.com/random/200x200?sig={{ $key+1 }}" class="d-block ui-w-40 ui-bordered mr-4" alt="">
                        <div class="media-body">
                          <a href="#" class="d-block text-dark">{{ $product->product_name }}</a>
                          <small>
                            <span class="text-muted">Color:</span>
                            <span class="ui-product-color ui-product-color-sm align-text-bottom" style="background:#e81e2c;"></span> &nbsp;
                            <span class="text-muted">Size: </span> EU 37 &nbsp;
                            <span class="text-muted">Ships from: </span> China
                          </small>
                        </div>
                      </div>
                    </td>
                    <td class="text-right font-weight-semibold align-middle p-4">Rp.{{ $product->price }}</td>
                    <td class="align-middle p-4"><input type="text" class="form-control text-center" value="{{ $product->qty }}" disabled></td>
                    <td class="text-right font-weight-semibold align-middle p-4">Rp.{{ $product->price*$product->qty }}</td>
                    <td class="text-center align-middle px-0"><a href="#" class="shop-tooltip close float-none text-danger" title="" data-original-title="Remove">×</a></td>
                  </tr>
                @endforeach
        
                </tbody>
              </table>
            </div>
            <!-- / Shopping cart table -->
        
            <div class="d-flex flex-wrap justify-content-between align-items-center pb-4">
              <div class="mt-4">
                {{-- <label class="text-muted font-weight-normal">Promocode</label>
                <input type="text" placeholder="ABC" class="form-control"> --}}
              </div>
              <div class="d-flex">
                {{-- <div class="text-right mt-4 mr-5">
                  <label class="text-muted font-weight-normal m-0">Discount</label>
                  <div class="text-large"><strong>$20</strong></div>
                </div> --}}
                <div class="text-right mt-4">
                  <label class="text-muted font-weight-normal m-0">Total price</label>
                  <div class="text-large"><strong>Rp.{{ $cart->getTotalAmount() }}</strong></div>
                </div>
              </div>
            </div>
        
            <div class="float-right">
              <button type="button" class="btn btn-lg btn-default md-btn-flat mt-2 mr-3">Back to shopping</button>
              <button type="button" class="btn btn-lg btn-primary mt-2" id="pay-button">Checkout</button>
            </div>
        
          </div>
      </div>
  </div>
@endsection
@push('scripts')
    <script type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
      <script type="text/javascript">
        // For example trigger on button clicked, or any time you need
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
          // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token
          window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
              /* You may add your own implementation here */
            //   alert("payment success!"); 
            paymentSuccess();
              // console.log(result);
              onProcess(result);
            },
            onPending: function(result){
              /* You may add your own implementation here */
              console.log("sat");
              alert("wating your payment!"); 
               onProcess(result);
              //  console.log(result);
            },
            onError: function(result){
              /* You may add your own implementation here */
              alert("payment failed!"); console.log(result);
            },
            onClose: function(){
              /* You may add your own implementation here */
              alert('you closed the popup without finishing the payment');
            }
          })
        });

        function onProcess(result){
          document.getElementById('value_payment').value = JSON.stringify(result);
          $('#process_payment').submit();
        }

        function paymentSuccess(){
            Swal.fire(
            'Success!',
            'Your Payment Successfully!',
            'success'
            )
        }
      </script>
@endpush