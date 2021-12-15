<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\HotelReservation;
use App\Reservation;
use App\RoomReservation;
use App\Components\OmnibeesApiComponent;
use Illuminate\Routing\Controller as BaseController;

class ReservationsController extends BaseController
{
	public function index()
	{
		$reservations = Reservation::all();
    	return view('admin.reservations',['reservations'=>$reservations]);
	}
	public function listing(Request $request)
	{
		$r = [];		
		extract($request->only(['order','offset','limit']));
		$reservations = Reservation::orderBy('id',$order);
		if(!\Auth::user()->isAdmin) $reservations->where('affiliateId',\Auth::user()->affiliateId);
		$r['total'] = $reservations->count();
		$reservations
		->offset($offset)
		->take($limit);
		$reservations = $reservations->get();
		$r['rows'] = [];
		foreach ($reservations as $res) {
			$r['rows'][]=[
				$res->id,
				$res->clientFirstName.' '.$res->clientLastName,
				$res->hotelReservation?$res->hotelReservation->hotel->name:'N/A',
				$res->hotelReservation?$res->hotelReservation->rooms->count().(!$res->hotelReservation->isPushed()?' (NO EMPUJADAS)':''):'N/A',
				$res->toursReservations()->count()?$this->getTourNames($res):'N/A',
				$res->transportReservations()->count()?$this->getTransportNames($res):'N/A',
				'$'.number_format($res->total,2).' '.$res->currencyCode,
				$res->created_at->toDateTimeString(),
				$res->affiliate->name,
				$res::$statusLabels[$res->status],
				"<a data-modal-width='800px' data-toggle='modal-dinamic' href='".url('admin/reservations/view',['id'=>$res->id])."' class='btn btn-primary'><i class='fa fa-eye'></i></a>",
			];
		}
		
		return response()->json($r);
	}
	public function getTourNames($res)
	{
		$names = [];
		foreach ($res->toursReservations as $tr) {
			$names[] = $tr->tourPrice->name;
		}

		return implode(', ', $names);
	}
	public function getTransportNames($res)
	{
		$names = [];
		foreach ($res->transportReservations as $tr) {
			$names[] = $tr->transportServiceType->name;
		}

		return implode(', ', $names);		
	}
	public function view($id)
	{
		$res = Reservation::find($id);
		return view('admin.reservations.view',['res'=>$res]);
	}
	public function cancelRoom($id)
	{
		$room = RoomReservation::find($id);
		$res = $room->hotelReservation;
		list($success,$cancellationPenaltyTotal) = OmnibeesApiComponent::doCancelRooms($res,[$room]);
		if ($success) {
			$res->status = HotelReservation::STATUS_MODIFIED;
			$res->save();
			$room->status = RoomReservation::STATUS_CANCELLED;
			$room->cancellationPenaltyTotal = $cancellationPenaltyTotal;
			$room->save();
		}
		return response()->json(['success'=>$success]);
	}
	public function cancel($id)
	{
		$res = HotelReservation::find($id);
		list($success,$cancellationPenaltyTotal) = OmnibeesApiComponent::doCancelRooms($res);
		if ($success) {
			$res->cancellationPenaltyTotal = $cancellationPenaltyTotal;
			$res->status = HotelReservation::STATUS_CANCELLED;
			foreach ($res->rooms as $room) {
				$room->status = RoomReservation::STATUS_CANCELLED;
				$room->save();
			}
			$res->save();
		}
		return response()->json(['success'=>$success]);
	}
	public function test()
	{
		$allRes = HotelReservation::all();
		foreach ($allRes as $res) {
			$refCode = '';
			foreach ($res->rooms as $room) {
				if($room->refCode!=''){
					$refCode = trim(explode('/',$room->refCode)[0]);
					break;
				}
			}
			$res->refCode = $refCode;
			$res->save();
		}
	}
}