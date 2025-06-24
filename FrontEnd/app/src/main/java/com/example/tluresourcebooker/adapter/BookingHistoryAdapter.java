package com.example.tluresourcebooker.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.core.content.ContextCompat;
import androidx.recyclerview.widget.RecyclerView;
import com.example.tluresourcebooker.R;
import com.example.tluresourcebooker.model.Booking;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;
import java.util.Locale;

public class BookingHistoryAdapter extends RecyclerView.Adapter<BookingHistoryAdapter.ViewHolder> {

    private List<Booking> bookingList;
    private Context context;

    public BookingHistoryAdapter(Context context, List<Booking> bookingList) {
        this.context = context;
        this.bookingList = bookingList;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_booking_history, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        Booking booking = bookingList.get(position);
        holder.bind(booking);
    }

    @Override
    public int getItemCount() {
        return bookingList.size();
    }

    public void updateData(List<Booking> newBookings) {
        this.bookingList.clear();
        this.bookingList.addAll(newBookings);
        notifyDataSetChanged();
    }

    class ViewHolder extends RecyclerView.ViewHolder {
        ImageView iconRoomType;
        TextView textViewRoomName, textViewBookingDate, textViewBookingTime, textViewBookingStatus;

        ViewHolder(View itemView) {
            super(itemView);
            iconRoomType = itemView.findViewById(R.id.iconRoomType);
            textViewRoomName = itemView.findViewById(R.id.textViewRoomNameHistory);
            textViewBookingDate = itemView.findViewById(R.id.textViewBookingDate);
            textViewBookingTime = itemView.findViewById(R.id.textViewBookingTime);
            textViewBookingStatus = itemView.findViewById(R.id.textViewBookingStatus);
        }

        void bind(Booking booking) {
            if (booking.getRoom() != null) {
                textViewRoomName.setText(booking.getRoom().getName());
            }

            // Định dạng ngày và giờ
            SimpleDateFormat inputFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault());
            SimpleDateFormat dateFormat = new SimpleDateFormat("dd/MM/yyyy", Locale.getDefault());
            SimpleDateFormat timeFormat = new SimpleDateFormat("HH:mm", Locale.getDefault());

            try {
                Date startDate = inputFormat.parse(booking.getStartTime());
                Date endDate = inputFormat.parse(booking.getEndTime());
                if (startDate != null && endDate != null) {
                    textViewBookingDate.setText(dateFormat.format(startDate));
                    String timeRange = timeFormat.format(startDate) + " - " + timeFormat.format(endDate);
                    textViewBookingTime.setText(timeRange);
                }
            } catch (ParseException e) {
                e.printStackTrace();
                textViewBookingDate.setText("N/A");
                textViewBookingTime.setText("N/A");
            }

            // Xử lý hiển thị và màu sắc cho trạng thái
            String status = booking.getStatus();
            if (status != null) {
                switch (status.toLowerCase()) {
                    case "approved": // Giả sử "Đã hoàn thành" là "approved"
                        textViewBookingStatus.setText("Đã hoàn thành");
                        textViewBookingStatus.setTextColor(ContextCompat.getColor(context, R.color.status_completed));
                        break;
                    case "cancelled":
                        textViewBookingStatus.setText("Đã hủy");
                        textViewBookingStatus.setTextColor(ContextCompat.getColor(context, R.color.status_cancelled));
                        break;
                    case "absent": // Giả sử có trạng thái "vắng mặt"
                        textViewBookingStatus.setText("Vắng mặt");
                        textViewBookingStatus.setTextColor(ContextCompat.getColor(context, R.color.status_absent));
                        break;
                    default:
                        textViewBookingStatus.setText(status);
                        textViewBookingStatus.setTextColor(ContextCompat.getColor(context, R.color.status_default));
                        break;
                }
            }
        }
    }
}