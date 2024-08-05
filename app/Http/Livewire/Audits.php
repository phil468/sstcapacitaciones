<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Audit;

class Audits extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $user_type, $user_id, $event, $auditable_type, $auditable_id, $old_values, $new_values, $url, $ip_address, $user_agent, $tags;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.audits.view', [
            'audits' => Audit::latest()
						->orWhere('user_type', 'LIKE', $keyWord)
						->orWhere('user_id', 'LIKE', $keyWord)
						->orWhere('event', 'LIKE', $keyWord)
						->orWhere('auditable_type', 'LIKE', $keyWord)
						->orWhere('auditable_id', 'LIKE', $keyWord)
						->orWhere('old_values', 'LIKE', $keyWord)
						->orWhere('new_values', 'LIKE', $keyWord)
						->orWhere('url', 'LIKE', $keyWord)
						->orWhere('ip_address', 'LIKE', $keyWord)
						->orWhere('user_agent', 'LIKE', $keyWord)
						->orWhere('tags', 'LIKE', $keyWord)
						->paginate(10),
        ]);
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->user_type = null;
		$this->user_id = null;
		$this->event = null;
		$this->auditable_type = null;
		$this->auditable_id = null;
		$this->old_values = null;
		$this->new_values = null;
		$this->url = null;
		$this->ip_address = null;
		$this->user_agent = null;
		$this->tags = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
		'event' => 'required',
		'auditable_type' => 'required',
		'auditable_id' => 'required',
        ]);

        Audit::create([ 
			'user_type' => $this-> user_type,
			'user_id' => $this-> user_id,
			'event' => $this-> event,
			'auditable_type' => $this-> auditable_type,
			'auditable_id' => $this-> auditable_id,
			'old_values' => $this-> old_values,
			'new_values' => $this-> new_values,
			'url' => $this-> url,
			'ip_address' => $this-> ip_address,
			'user_agent' => $this-> user_agent,
			'tags' => $this-> tags
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Audit creado correctamente.');
    }

    public function edit($id)
    {
        $record = Audit::findOrFail($id);

        $this->selected_id = $id; 
		$this->user_type = $record-> user_type;
		$this->user_id = $record-> user_id;
		$this->event = $record-> event;
		$this->auditable_type = $record-> auditable_type;
		$this->auditable_id = $record-> auditable_id;
		$this->old_values = $record-> old_values;
		$this->new_values = $record-> new_values;
		$this->url = $record-> url;
		$this->ip_address = $record-> ip_address;
		$this->user_agent = $record-> user_agent;
		$this->tags = $record-> tags;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
		'event' => 'required',
		'auditable_type' => 'required',
		'auditable_id' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Audit::find($this->selected_id);
            $record->update([ 
			'user_type' => $this-> user_type,
			'user_id' => $this-> user_id,
			'event' => $this-> event,
			'auditable_type' => $this-> auditable_type,
			'auditable_id' => $this-> auditable_id,
			'old_values' => $this-> old_values,
			'new_values' => $this-> new_values,
			'url' => $this-> url,
			'ip_address' => $this-> ip_address,
			'user_agent' => $this-> user_agent,
			'tags' => $this-> tags
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Audit actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Audit::where('id', $id);
            $record->delete();
        }
    }
}
