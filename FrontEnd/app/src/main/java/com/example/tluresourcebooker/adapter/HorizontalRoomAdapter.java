package com.example.tluresourcebooker.adapter;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.bumptech.glide.Glide;
import com.example.tluresourcebooker.R;
import com.example.tluresourcebooker.model.Room;
import java.util.List;
import java.util.Locale;

public class HorizontalRoomAdapter extends RecyclerView.Adapter<HorizontalRoomAdapter.RoomViewHolder> {

    private List<Room> roomList;
    private OnRoomClickListener listener;

    public interface OnRoomClickListener {
        void onRoomClick(Room room);
    }

    public HorizontalRoomAdapter(List<Room> roomList, OnRoomClickListener listener) {
        this.roomList = roomList;
        this.listener = listener;
    }

    @NonNull
    @Override
    public RoomViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_room_card, parent, false);
        return new RoomViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull RoomViewHolder holder, int position) {
        Room room = roomList.get(position);
        // TRUYỀN CẢ room VÀ listener VÀO HÀM BIND
        holder.bind(room, listener);
    }

    @Override
    public int getItemCount() {
        return roomList != null ? roomList.size() : 0;
    }

    // Lớp ViewHolder không cần 'static' nữa nếu bạn muốn truy cập trực tiếp listener,
    // nhưng cách truyền listener vào vẫn là tốt nhất.
    static class RoomViewHolder extends RecyclerView.ViewHolder {
        ImageView roomImageView;
        TextView roomNameTextView;
        TextView roomInfoTextView;

        public RoomViewHolder(@NonNull View itemView) {
            super(itemView);
            roomImageView = itemView.findViewById(R.id.roomImageView);
            roomNameTextView = itemView.findViewById(R.id.textViewRoomName);
            roomInfoTextView = itemView.findViewById(R.id.textViewRoomInfo);
        }

        // === SỬA LẠI ĐỊNH NGHĨA HÀM BIND ĐỂ NHẬN LISTENER ===
        void bind(final Room room, final OnRoomClickListener listener) {
            roomNameTextView.setText(room.getName());
            if (room.getCapacity() != null) {
                String info = String.format(Locale.getDefault(), "Sức chứa: %d", room.getCapacity());
                roomInfoTextView.setText(info);
            } else {
                roomInfoTextView.setText("Sức chứa: N/A");
            }

            if (room.getImages() != null && !room.getImages().isEmpty()) {
                Glide.with(itemView.getContext())
                        .load(room.getImages().get(0))
                        .placeholder(R.color.grey_200)
                        .error(R.color.grey_200)
                        .into(roomImageView);
            } else {
                roomImageView.setImageResource(R.color.grey_200);
            }

            // Gán sự kiện click cho toàn bộ item
            itemView.setOnClickListener(v -> {
                if (listener != null) {
                    listener.onRoomClick(room);
                }
            });
        }
    }
}