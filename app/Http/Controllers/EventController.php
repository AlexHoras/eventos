<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\User;

class EventController extends Controller
{
    function index(){

        $search = request('search');

        if($search){

            $events = Event::where([
                ['title', 'like', '%'.$search.'%']               
            ])->get();

        } else {
            $events = Event::all();
        }

        return view('welcome', ['events' => $events, 'search' => $search ]);

    }

    function create(){

        return view('events.create');

    }

    public function dashboard(){
        $user = auth()->user();

        $events = $user->events;

        $eventAsParticipant = $user->eventAsParticipant;

        return view('events.dashboard', ['events' => $events, 'eventAsParticipant' => $eventAsParticipant ]);
 
    }

    public function store(Request $request){
      
        $event = new Event;      
        
        $event->title = $request->title;
        $event->date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->descrition = $request->description;
        $event->items = $request->items;

        // Image upload
        if($request->hasFile('image') && $request->file('image')->isValid()){

            $requestImage = $request->image;

            $extencion = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now") . "." . $extencion);

            $requestImage->move(public_path('img/events'), $imageName);

            $event->image = $imageName;
        }

        /**
         * Pega o ID do usuario logado retornando um array
         * auth()->user()
         */
        $user = auth()->user();
        $event->user_id = $user->id;

        $event->save();      
        
        return redirect('/')->with('msg', 'Evento criado com sucesso!');

    }

    public function show($id){

        $event = Event::findOrFail($id);

        $eventOwner = User::where('id', $event->user_id)->first()->toArray();  

        $user = auth()->user();
        $hasUserJoined = false;

        if($user){
            $userEvents = $user->eventAsParticipant->toArray();

            foreach($userEvents as $userEvent){
                if($userEvent['id'] == $id){
                    $hasUserJoined = true;
                }
            }
        }

        return view('events.show', ['event' => $event, 'eventOwner' => $eventOwner, 'hasUserJoined' => $hasUserJoined]);
    }

    public function destroy($id){

        Event::findOrFail($id)->delete();

        return redirect('/dashboard')->with('msg', 'Evento excliudo com sucesso');

    }

    public function edit($id)
    {
        $user = auth()->user();

        $event = Event::findOrFail($id);

        if ($user->id != $event->user_id) {
            return redirect('/dashboard');
        }

        return view('events.edit', ['event' => $event]);
    }

    public function update(Request $request) {

        $data = $request->all();

         // Image upload
        if($request->hasFile('image') && $request->file('image')->isValid()){

            $requestImage = $request->image;

            $extencion = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now") . "." . $extencion);

            $requestImage->move(public_path('img/events'), $imageName);

            $data['image'] = $imageName;
        }

        Event::findOrFail($request->id)->update($data); 

        return redirect('/dashboard')->with('msg', 'Evento editado com sucesso');
    }

    public function joinEvent($id){
        $user = auth()->user();

        $user->eventAsParticipant()->attach($id);

        $event = Event::findOrFail($id); 

        return redirect('/dashboard')->with('msg', 'Sua presença esta confimada no evento '. $event->title);
    }

    public function leaveEvent($id){
        $user = auth()->user();

        $user->eventAsParticipant()->detach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('msg', 'Voce sai com sucesso do evento:  '. $event->title);
    }
}

