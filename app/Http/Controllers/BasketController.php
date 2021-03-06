<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{
    public function basket(){
        $orderId = session('orderId');
        if(is_null($orderId)) {
            return redirect()->route('home');
        }
        $order = Order::findOrFail($orderId);
        return view('basket', compact('order'));
    }

    public function basketPlace(){
        $orderId = session('orderId');
        if (is_null($orderId)){
            return redirect()->route('home');
        }
        $order = Order::find($orderId);
        return view('order', compact('order'));
    }


    public function basketConfirm(Request $request)
    {
        $orderId = session('orderId');
        if (is_null($orderId)){
            return redirect()->route('home');
        }
        $order = Order::find($orderId);
        $success = $order->saveOrder($request->name,$request->phone);

        if($success){
            session()->flash('success','Ваш заказ принят на обработку!');
        } else {
            session()->flash('warning','Случилась ошибка');
        }



        return redirect()->route('home');
    }

    public function basketAdd($productId)
    {
        $orderId = session('orderId');
        if(is_null($orderId)){
            //создаём модель Ордер и закидываем в сессию его id
            $order = Order::create();
            session(['orderId' => $order->id]);
        }else{
            //выбираем наш Ордер
            $order = Order::find($orderId);
        }
        //проверка есть ли эта хуйня в ордере
        if($order->products->contains($productId)){
            $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;
            $pivotRow->count++;
            $pivotRow->update();
        }else{
            $order->products()->attach($productId);
        }

        if(Auth::check()){
            $order->user_id = Auth::id();
            $order->save();
        }

        $product = Product::find($productId);
        session()->flash('success','Добавлен Товар ' . $product->name);

        return redirect()->route('basket');
    }

    public function basketRemove($productId)
    {
        $orderId = session('orderId');
        if(is_null($orderId)){
            return redirect()->route('basket');
        }
        $order = Order::find($orderId);

        if($order->products->contains($productId)) {
            $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;
            if ($pivotRow->count < 2){
                $order->products()->detach($productId);
            } else {
            $pivotRow->count--;
            $pivotRow->update();
            }
        }

        //удалён товар i
        $product = Product::find($productId);
        session()->flash('warning','Удалён Товар ' . $product->name);

        return redirect()->route('basket');
    }

}
