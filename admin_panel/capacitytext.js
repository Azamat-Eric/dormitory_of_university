export function capacityText(main_capacity){
    let room_capacity_labels = document.querySelectorAll(".room-capacity");
    room_capacity_labels.forEach(room_capacity_label=>{
        let capacity_span = room_capacity_label.querySelector("span");
        capacity_span.textContent = main_capacity.textContent;
    });
}
