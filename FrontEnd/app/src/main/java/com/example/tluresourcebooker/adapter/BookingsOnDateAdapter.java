package com.example.tluresourcebooker.adapter;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.example.tluresourcebooker.R;
import com.example.tluresourcebooker.model.Booking;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;
import java.util.Locale;

public class BookingsOnDateAdapter extends RecyclerView.Adapter<BookingsOnDateAdapter.ViewHolder> {

    private List<Booking> bookings;

    public BookingsOnDateAdapter(List<Booking> bookings) {
        this.bookings = bookings;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_booking_for_date, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        holder.bind(bookings.get(position));
    }

    @Override
    public int getItemCount() {
        return bookings.size();
    }

    static class ViewHolder extends RecyclerView.ViewHolder {
        TextView roomName, bookingTime, bookingPurpose;

        ViewHolder(View itemView) {
            super(itemView);
            roomName = itemView.findViewById(R.id.textViewRoomName);
            bookingTime = itemView.findViewById(R.id.textViewBookingTime);
            bookingPurpose = itemView.findViewById(R.id.textViewBookingPurpose);
        }

        void bind(Booking booking) {
            if (booking.getRoom() != null) {
                roomName.setText(booking.getRoom().getName());
            }
            if (booking.getPurpose() != null) {
                bookingPurpose.setText("Mục đích: " + booking.getPurpose());
            }

            SimpleDateFormat inputFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault());
            SimpleDateFormat timeFormat = new SimpleDateFormat("HH:mm", Locale.getDefault());

            try {
                Date startDate = inputFormat.parse(booking.getStartTime());
                Date endDate = inputFormat.parse(booking.getEndTime());
                if (startDate != null && endDate != null) {
                    String timeRange = timeFormat.format(startDate) + " - " + timeFormat.format(endDate);
                    bookingTime.setText(timeRange);
                }
            } catch (ParseException e) {
                e.printStackTrace();
            }
        }
    }
}