document.addEventListener('livewire:load', () => {
    attachCrearUsuarioHandler();

    // Re-enganchar tras cada actualización del componente
    Livewire.hook('message.processed', (message, component) => {
        attachCrearUsuarioHandler();
    });
});

function attachCrearUsuarioHandler(){
    // Evitar duplicar
    if(window._crearUsuarioHandlerAttached) return;
    window._crearUsuarioHandlerAttached = true;

    document.addEventListener('click', function(e){
        const btn = e.target.closest('.crear-usuario-btn');
        if(!btn) return;

        const personalId = btn.getAttribute('data-personal');
        Swal.fire({
            title: 'Crear Usuario',
            input: 'email',
            inputLabel: 'Correo empresarial',
            inputPlaceholder: 'email@empresa.com',
            showCancelButton: true,
            confirmButtonText: 'Crear',
            inputValidator: (value) => {
                if (!value) return 'Ingrese un email';
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if(!re.test(value)) return 'Email no válido';
            }
        }).then(res=>{
            if(!res.isConfirmed) return;
            Swal.showLoading();

            fetch(URL_UPDATE_PERSONAL.replace(':id', personalId),{
                method:'PATCH',
                headers:{
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    correo_empresa: res.value,
                    actualizar_user: true
                })
            })
            .then(r=>r.json())
            .then(json=>{
                if(json.success){
                    Swal.fire({
                        icon:'success',
                        title:'Usuario creado',
                        html:`Email: <b>${json.personal.user?.email || res.value}</b><br>${json.password_generada ? 'Password: <code>'+json.password_generada+'</code>' : ''}`
                    });
                    window.livewire && window.livewire.emit('refrescarRegistroTable');
                } else {
                    Swal.fire('Error', json.message || 'No se pudo crear', 'error');
                }
            })
            .catch(()=>{
                Swal.fire('Error','Fallo de red','error');
            });
        });
    });
}