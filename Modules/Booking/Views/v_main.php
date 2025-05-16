<input type="text" id="mydate" gldp-id="mydate" />
<div gldp-el="mydate"
  style="width:400px; height:300px; position:absolute; top:70px; left:100px;">
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="/introvert_tasks/assets/glDatePicker-2.0/glDatePicker.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', async () => {
    const MAX_BOOKINGS_PER_DAY = 5;
    const currentDate = new Date();

    try {
        const bookingResponse = await fetch('booking/dates');
        
        if (!bookingResponse.ok) {
            throw new Error('Failed to fetch booking data');
        }

        const bookingsData = await bookingResponse.json();

        const fullyBookedDates = [];
        const availableBookingSlots = [];

        for (const [dateString, bookingsCount] of Object.entries(bookingsData)) {
            const [year, month, day] = dateString.split('-').map(Number);
            const parsedDate = new Date(year, month - 1, day);

            if (bookingsCount >= MAX_BOOKINGS_PER_DAY) {
                fullyBookedDates.push(parsedDate);
            } else {
                availableBookingSlots.push({
                    date: parsedDate,
                    remainingSlots: MAX_BOOKINGS_PER_DAY - bookingsCount
                });
            }
        }

        $('input').glDatePicker({
            cssName: 'default',
            showAlways: true,
            selectedDate: currentDate,
            selectableDates: availableBookingSlots.map(slot => ({
                date: slot.date,
                data: { 
                    available: true,
                    slotsLeft: slot.remainingSlots 
                },
                repeatYear: false
            })),
            disabledDates: fullyBookedDates.map(date => ({
                date: date,
                data: { available: false },
                repeatYear: false
            })),
            onClick: (element, cell, date, dateData) => {
                if (dateData?.available) {
                    const formattedDate = date.toLocaleDateString();
                    const datepickerInput = document.querySelector('input');
                    datepickerInput.value = formattedDate;
                  
                    console.log(`Available slots: ${dateData.slotsLeft}`);
                }
            }
        });
    } catch (error) {
        console.error('Booking calendar initialization error:', error);
    }
  });
</script>